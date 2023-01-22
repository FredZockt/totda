<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        $x = 1;
        $y = 1;
        $max_x = 100;
        $max_y = 50;
        for ($i = 0; $i < 20; $i++) {
            $city = new City();
            $city->name = "City " . ($i + 1);
            $city->kingdom_id = 1;
            $city->tax_rate = rand(5, 20) / 100;
            $city->x = $x;
            $city->y = $y;

            // maximum number of attempts to find a new location that is not already occupied by another city
            $attempts = 0;
            $max_attempts = 100;
            while (City::where('x', $city->x)->where('y', $city->y)->first() && $attempts < $max_attempts) {
                $x += rand(3, 5);
                $y += rand(3, 5);
                if ($x > $max_x) {
                    $x = 1;
                }
                if ($y > $max_y) {
                    $y = 1;
                }
                $attempts++;
            }

            if ($attempts < $max_attempts) {
                $city->save();
            }

        }
        //Reset the x and y coordinates
        $x = 1;
        $y = 51;
        $max_y = 100;
        for ($i = 20; $i < 40; $i++) {
            $city = new City();
            $city->name = "City " . ($i + 1);
            $city->kingdom_id = 2;
            $city->tax_rate = rand(5, 20) / 100;
            $city->x = $x;
            $city->y = $y;

            // maximum number of attempts to find a new location that is not already occupied by another city
            $attempts = 0;
            $max_attempts = 100;
            while (City::where('x', $city->x)->where('y', $city->y)->first() && $attempts < $max_attempts) {
                $x += rand(3, 5);
                $y += rand(3, 5);
                if ($x > $max_x) {
                    $x = 1;
                }
                if ($y > $max_y) {
                    $y = 51;
                }
                $attempts++;
            }

            if ($attempts < $max_attempts) {
                $city->save();
            }
        }
    }
}
