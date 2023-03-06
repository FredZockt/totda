<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use App\Models\Building;
use App\Models\Good;

class BuildingsTableSeeder extends Seeder
{
    public function run()
    {
        $cities = City::all();
        $goods = Good::all();

        foreach($cities as $city) {
            if($city->resources) {
                foreach (json_decode($city->resources) as $city_resource) {
                    foreach($goods as $good) {
                        if($good->name == $city_resource) {
                            $building = new Building();
                            $building->name = $good->name . '_factory';
                            $building->good_id = $good->id;
                            $building->city_id = $city->id;
                            $building->user_id = null;
                            $price = $good->price;
                            $building->short_job = ceil((rand(300, 600) * (1 + $price/100)) / 300) * 300;
                            $building->mid_job = ceil((rand(3600, 7200) * (1 + $price/100)) / 3600) * 3600;
                            $building->long_job = ceil((rand(14400, 28800) * (1 + $price/100)) / 14400) * 14400;
                            $building->save();
                        }
                    }
                }
            }
        }
    }
}
