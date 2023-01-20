<?php

namespace Database\Seeders;

use App\Models\Economy;
use App\Models\Good;
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

        foreach ($goods as $good) {
            $economy = new Economy();
            $economy->good_id = $good->id;
            $economy->price = $good->price;
            $economy->quantity = rand(1000, 1500);
            $economy->save();
        }
    }
}
