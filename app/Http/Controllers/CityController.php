<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $city = $user->currentCity()->first();
        $buildings = Building::leftJoin('goods', 'goods.id', '=', 'buildings.good_id')->where('city_id', $city->id)->get([
            'goods.name as good_name',
            'buildings.*'
        ]);
        $workFlag = !!$user->job_id;
        $walkFlag = false;
        $governor = $city->governor()->first();
        $application = DB::table('governor_application')->where('user_id', $user->id)->first();
        $vacancy = DB::table('vacancies')->where('city_id', $city->id)->first();

        if($vacancy) {
            if(Carbon::createFromTimeString($vacancy->open_until)->timestamp <=  Carbon::now()->timestamp) {
                $vacancy = null;
            }
        }

        if($user->job_id == 1) {
            $walkFlag = true;
        }

        foreach($buildings as $building) {
            $building->short_job = $city->getReadableWalktime($building->short_job);
            $building->mid_job = $city->getReadableWalktime($building->mid_job);
            $building->long_job = $city->getReadableWalktime($building->long_job);
        }

        return view('city.index', [
            'buildings' => $buildings,
            'city' => $city,
            'workFlag' => $workFlag,
            'walkFlag' => $walkFlag,
            'governor' => $governor,
            'user' => $user,
            'application' => $application,
            'vacancy' => $vacancy
        ]);
    }

    public function apply()
    {
        $user = auth()->user()->first();
        $city = $user->currentCity()->first();
        $kingdom = $city->kingdom()->first();
        $application = DB::table('governor_application');

        if($user->kingdom_id != $city->kingdom_id) {
            return redirect()->back()->with([
                'status' => 'You cannot apply here.',
                'status_type' => 'danger'
            ]);
        }

        if($application->where('user_id', $user->id)->first() != null) {
            return redirect()->back()->with([
                'status' => 'You already applied as governor.',
                'status_type' => 'danger'
            ]);
        }

        if($user->gold > 500) {
            // user has to pay a fee
            $user->gold -= 500;
            $user->save();

            // kingdom gets the fee
            $kingdom->gold += 500;
            $kingdom->save();

            // save applicant
            $application->insert([
                'user_id' => $user->id,
                'city_id' => $city->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

        } else {
            return redirect()->back()->with([
                'status' => 'You need 500 gold to apply',
                'status_type' => 'danger'
            ]);
        }

        return redirect()->back()->with([
            'status' => 'You successfully applied as governor!',
            'status_type' => 'success'
        ]);
    }

    public function cancel()
    {
        $user = auth()->user()->first();
        $city = $user->currentCity()->first();
        $application = DB::table('governor_application')->where('user_id', $user->id)->where('city_id', $city->id);

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

    public function depose($city_id)
    {
        $city = City::find($city_id);
        $kingdom = $city->kingdom()->first();

        if($kingdom->king_id != auth()->user()->id) {
            return redirect()->back()->with([
                'status' => 'Not allowed.',
                'status_type' => 'danger'
            ]);
        }

        $city->governor_id = null;
        $city->save();

        return redirect()->back()->with([
            'status' => 'You deposed the governor of ' . $city->name,
            'status_type' => 'success'
        ]);
    }

    public function appoint($city_id, $user_id)
    {
        $city = City::find($city_id);
        $kingdom = $city->kingdom()->first();

        if($kingdom->king_id != auth()->user()->id) {
            return redirect()->back()->with([
                'status' => 'Not allowed.',
                'status_type' => 'danger'
            ]);
        }

        $city->governor_id = $user_id;
        $city->save();

        DB::table('governor_application')->where('city_id', $city_id)->delete();

        return redirect()->back()->with([
            'status' => 'You appointed the governor of ' . $city->name,
            'status_type' => 'success'
        ]);
    }

    public function tax(Request $request)
    {
        $rate = $request->input('rate');
        $user = auth()->user()->first();
        $city = $user->currentCity()->first();

        if($user->id != $city->governor()->first()->id) {
            return redirect()->back()->with([
                'status' => 'Not allowed.',
                'status_type' => 'danger'
            ]);
        }

        if($rate < 0.01 || $rate > 5.00) {
            return redirect()->back()->with([
                'status' => 'Not allowed value',
                'status_type' => 'danger'
            ]);
        }

        $city->tax_rate = $rate;
        $city->save();

        return redirect()->back()->with([
            'status' => 'Tax rate adjusted',
            'status_type' => 'success'
        ]);
    }

    public function abdicate()
    {
        $user = auth()->user()->first();
        $city = City::where('governor_id', $user->id)->first();

        if(!$city) {
            return redirect()->back()->with([
                'status' => 'You are no governor',
                'status_type' => 'danger'
            ]);
        }

        $city->governor_id = null;
        $city->save();

        return redirect()->back()->with([
            'status' => 'You have abdicated',
            'status_type' => 'success'
        ]);
    }


}
