<?php

namespace App\Http\Controllers;

use App\Models\City;

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

        if(!$user->job_id && $city && $current_city) {

        } else {
            return redirect('/home');
        }
    }
}
