<?php

namespace App\Http\Controllers;

use App\Models\City;
use Carbon\Carbon;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function walk($city_id)
    {
        $user = auth()->user();
        $city = City::find($city_id);
        $current_city = $user->currentCity()->first();

        if($user->job_id) {
            return redirect('/city')->with([
                'status' => 'You are currently busy',
                'status_type' => 'danger'
            ]);
        }

        if($city && $current_city && $city != $current_city) {
            $distanceInSeconds = $city->calculateWalktime($city->calculateDistance($current_city->id, $city->id));
            $user->job_id = 1;
            $user->current_city_id = $city->id;
            $user->work_finished_at = Carbon::now()->addSeconds($distanceInSeconds);
            $user->save();

            session()->put([
                'active_job_headline' => 'You are walking to another city',
                'active_job_description' => 'journey to: ' . $city->name . '. Arrival at: ' . $user->work_finished_at,
            ]);

            return redirect()->back()->with([
                'status' => 'You start your journey to: ' . $city->name . '. Arrival at: ' . $user->work_finished_at,
                'status_type' => 'success'
            ]);
        } else {
            return redirect()->back()->with([
                'status' => 'something went wrong...',
                'status_type' => 'danger'
            ]);
        }
    }
}
