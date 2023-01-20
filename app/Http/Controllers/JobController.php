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
            return redirect('/home')->with([
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
            return redirect()->back();
        } else {
            return redirect('/home');
        }
    }
}
