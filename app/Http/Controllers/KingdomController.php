<?php

namespace App\Http\Controllers;

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
        $kingdom = auth()->user()->kingdom()->first();
        $cities = $kingdom->cities()->get();

        foreach($cities as $index => $city) {
            $city->distanceToInKm = $city->calculateDistance(auth()->user()->current_city_id, $city->id);
            $city->distanceToInSeconds = $city->calculateWalktime($city->distanceToInKm);
            $city->distanceToAsReadable = $city->getReadableWalktime($city->distanceToInSeconds);
            $city->distanceToAsDate = Carbon::now()->addSeconds($city->distanceToInSeconds)->toDateTimeString();
        }

        return view('kingdom.index', [
            'kingdom' => $kingdom,
            'cities' => $cities
        ]);
    }
}
