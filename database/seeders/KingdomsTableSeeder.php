<?php

namespace Database\Seeders;

use App\Models\Kingdom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KingdomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kingdomA = new Kingdom();
        $kingdomA->name = 'Kingdom A';
        $kingdomA->gold = 1000;
        $kingdomA->save();

        $kingdomB = new Kingdom();
        $kingdomB->name = 'Kingdom B';
        $kingdomB->gold = 1000;
        $kingdomB->save();
    }
}
