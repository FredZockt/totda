<?php

namespace Database\Seeders;

use App\Models\Economy;
use App\Models\Good;
use App\Models\City;
use Illuminate\Database\Seeder;

class EconomyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $goods = Good::all();
        $cities = City::all();

        foreach($cities as $city) {
            foreach ($goods as $good) {
                $economy = new Economy();
                $economy->good_id = $good->id;
                $economy->city_id = $city->id;
                $economy->price = $good->price;
                $economy->quantity = rand(333, 1337);
                $economy->save();
            }
        }

    }
}
