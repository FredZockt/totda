<?php

namespace Database\Seeders;

use App\Models\Kingdom;
use App\Models\Troops;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TroopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = Unit::all();
        $kingdoms = Kingdom::all();

        foreach ($kingdoms as $kingdom) {
            foreach($units as $unit) {
                $troop = new Troops();
                $troop->kingdom_id = $kingdom->id;
                $troop->unit_id = $unit->id;
                $troop->save();
            }
        }

    }
}
