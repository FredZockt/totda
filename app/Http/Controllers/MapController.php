<?php

namespace App\Http\Controllers;

use App\Models\City;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cities = City::all();

        foreach($cities as $index => $city) {
            $city->kingdom = $city->kingdom()->first()->name;
            if(!auth()->user()->job_id) {
                $city->distanceToInKm = $city->calculateDistance(auth()->user()->current_city_id, $city->id);
                $city->distanceToInSeconds = $city->calculateWalktime($city->distanceToInKm);
                $city->distanceToAsReadable = $city->getReadableWalktime($city->distanceToInSeconds);
                $city->distanceToAsDate = Carbon::now()->addSeconds($city->distanceToInSeconds)->toDateTimeString();
            }
        }

        return view('map.index', [
            "cities" => $cities
        ]);
    }
}
