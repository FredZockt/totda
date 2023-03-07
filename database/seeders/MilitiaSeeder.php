<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Militia;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class MilitiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = Unit::all();
        $cities = City::all();

        foreach($cities as $city) {
            foreach($units as $unit) {
                $militia = new Militia();
                $militia->city_id = $city->id;
                $militia->unit_id = $unit->id;
                $militia->save();
            }
        }

    }
}
