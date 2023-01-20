<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

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
            'walkFlag' => $walkFlag
        ]);
    }
}
