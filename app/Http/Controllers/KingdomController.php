<?php

namespace App\Http\Controllers;

use App\Models\Troops;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KingdomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $kingdom = auth()->user()->kingdom()->first();
        $cities = $kingdom->cities()->get();
        $king = $kingdom->king()->first();
        $governor_applicants = null;
        $application = DB::table('king_application')->where('user_id', $user->id)->first();
        $applicants = DB::table('king_application')
            ->leftJoin('users', 'users.id', '=', 'king_application.user_id')
            ->where('king_application.kingdom_id', $kingdom->id)
            ->select('king_application.*', 'users.*')
            ->get();
        $vacancy = DB::table('vacancies')->where('kingdom_id', $kingdom->id)->where('city_id', null)->first();
        $units = [];
        $troops = [];

        if($user->id == $kingdom->king_id) {
            foreach($cities as $city) {
                $governor_applicants[$city->id] = DB::table('governor_application')
                    ->leftJoin('users', 'users.id', '=', 'governor_application.user_id')
                    ->where('city_id', $city->id)
                    ->get();
            }
            $units = Unit::all();
            $troops = Troops::where('kingdom_id', $kingdom->id)->get();
        }

        foreach($applicants as $applicant) {
            $applicant->votings = DB::table('king_voting')->where('kings_applicant_id', $applicant->user_id)->count();
        }

        if($vacancy) {
            if(Carbon::createFromTimeString($vacancy->open_until)->timestamp <=  Carbon::now()->timestamp) {
                $vacancy = null;
            }
        }

        if(!auth()->user()->job_id) {
            foreach($cities as $index => $city) {
                $city->distanceToInKm = $city->calculateDistance(auth()->user()->current_city_id, $city->id);
                $city->distanceToInSeconds = $city->calculateWalktime($city->distanceToInKm);
                $city->distanceToAsReadable = $city->getReadableWalktime($city->distanceToInSeconds);
                $city->distanceToAsDate = Carbon::now()->addSeconds($city->distanceToInSeconds)->toDateTimeString();
            }
        }

        return view('kingdom.index', [
            'kingdom' => $kingdom,
            'cities' => $cities,
            'king' => $king,
            'user' => $user,
            'application' => $application,
            'vacancy' => $vacancy,
            'applicants' => $applicants,
            'governor_applicants' => $governor_applicants,
            'units' => $units,
            'troops' => $troops,
        ]);
    }

    public function apply()
    {
        $user = auth()->user()->first();
        $kingdom = $user->kingdom()->first();
        $application = DB::table('king_application');

        if($application->where('user_id', $user->id)->first() != null) {
            return redirect()->back()->with([
                'status' => 'You already applied as king/queen.',
                'status_type' => 'danger'
            ]);
        }

        if($user->gold > 5000) {
            // user has to pay a fee
            $user->gold -= 5000;
            $user->save();

            // kingdom gets the fee
            $kingdom->gold += 5000;
            $kingdom->save();

            // save applicant
            $application->insert([
                'user_id' => $user->id,
                'kingdom_id' => $kingdom->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

        } else {
            return redirect()->back()->with([
                'status' => 'You need 5000 gold to apply',
                'status_type' => 'danger'
            ]);
        }

        return redirect()->back()->with([
            'status' => 'You successfully applied as king/queen!',
            'status_type' => 'success'
        ]);
    }

    public function cancel()
    {
        $user = auth()->user()->first();
        $application = DB::table('king_application')->where('user_id', $user->id);

        if($application->first() == null) {
            return redirect()->back()->with([
                'status' => 'There is nothing to withdraw.',
                'status_type' => 'danger'
            ]);
        }

        $application->delete();
        return redirect()->back()->with([
            'status' => 'Application withdrawn.',
            'status_type' => 'warning'
        ]);
    }

    public function vote($applicant_id)
    {
        $user = auth()->user()->first();
        $applicant = DB::table('king_application')->where('user_id', $applicant_id)->first();
        $existingVote = DB::table('king_voting')->where('user_id', $user->id)->first();

        if(!$applicant) {
            return redirect()->back()->with([
                'status' => 'Applicant not found.',
                'status_type' => 'warning'
            ]);
        }

        if($existingVote) {
            DB::table('king_voting')->where('user_id', $user->id)->update([
                'kings_applicant_id' => $applicant_id
            ]);

            return redirect()->back()->with([
                'status' => 'Your voting was updated.',
                'status_type' => 'success'
            ]);
        } else {
            DB::table('king_voting')->insert([
                'kings_applicant_id' => $applicant_id,
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with([
                'status' => 'You voted!',
                'status_type' => 'success'
            ]);
        }

    }

    public function abdicate()
    {
        $user = auth()->user()->first();
        $kingdom = $user->kingdom()->first();
        $kingdom->king_id = null;
        $kingdom->save();

        return redirect()->back()->with([
            'status' => 'You have abdicated',
            'status_type' => 'success'
        ]);
    }
}
