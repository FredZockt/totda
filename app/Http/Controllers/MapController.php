<?php

namespace App\Http\Controllers;

use App\Models\City;
use BlackScorp\SimplexNoise\Noise2D;
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
        $noise2D = new Noise2D(.0145, 4, .5, 1.75);
        $map = array();
        for($x = 0; $x <= 100; $x++) {
            for($y = 0; $y <= 100; $y++) {
                $map[$x][$y]['value'] = $noise2D->getGreyValue($x, $y);
                $map[$x][$y]['bg'] = 'darkblue';
                if($map[$x][$y]['value'] < 25 ) {
                    $map[$x][$y]['bg'] = 'darkblue';
                } elseif ($map[$x][$y]['value'] >= 25 && $map[$x][$y]['value'] <= 49) {
                    $map[$x][$y]['bg'] = 'lightblue';
                } elseif ($map[$x][$y]['value'] >= 50 && $map[$x][$y]['value'] <= 74) {
                    $map[$x][$y]['bg'] = 'lightyellow';
                } elseif ($map[$x][$y]['value'] >= 75 && $map[$x][$y]['value'] <= 99) {
                    $map[$x][$y]['bg'] = 'lightgreen';
                } elseif ($map[$x][$y]['value'] >= 100 && $map[$x][$y]['value'] <= 124) {
                    $map[$x][$y]['bg'] = 'green';
                } elseif ($map[$x][$y]['value'] >= 125 && $map[$x][$y]['value'] <= 149) {
                    $map[$x][$y]['bg'] = 'darkgreen';
                } elseif ($map[$x][$y]['value'] >= 150 && $map[$x][$y]['value'] <= 174) {
                    $map[$x][$y]['bg'] = 'gray';
                } elseif ($map[$x][$y]['value'] >= 175 && $map[$x][$y]['value'] <= 199) {
                    $map[$x][$y]['bg'] = 'darkgray';
                } elseif ($map[$x][$y]['value'] >= 200 && $map[$x][$y]['value'] <= 224) {
                    $map[$x][$y]['bg'] = 'lightgray';
                } elseif ($map[$x][$y]['value'] >= 225) {
                    $map[$x][$y]['bg'] = 'white';
                }
            }
        }

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
            "cities" => $cities,
            "map" => $map,
        ]);
    }
}
