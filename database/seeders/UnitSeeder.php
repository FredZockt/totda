<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i < 11; $i++) {
            $unit = new Unit();
            $unit->name = 'Unit ' . $i;
            $unit->attack = rand(10, 100);
            $unit->defense = rand(10, 100);
            $unit->cost = rand(1, 2);
            $unit->save();
        }
    }
}
