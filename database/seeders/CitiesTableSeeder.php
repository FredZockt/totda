<?php

namespace Database\Seeders;

use App\Models\City;
use BlackScorp\SimplexNoise\Noise2D;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        $x = 1;
        $y = 1;
        $max_x = 100;
        $max_y = 50;
        $noise2D = new Noise2D(.0145, 4, .5, 1.75);

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
            while (City::where('x', $city->x)->where('y', $city->y)->first() && $attempts < $max_attempts || $noise2D->getGreyValue($x, $y) <= 49 || $noise2D->getGreyValue($x, $y) >= 225) {
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
                if ($noise2D->getGreyValue($x, $y) >= 50 && $noise2D->getGreyValue($x, $y) <= 74) {
                    $city->resources = json_encode(['fish', 'salt', 'water']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 75 && $noise2D->getGreyValue($x, $y) <= 99) {
                    $city->resources = json_encode(['wheat']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 100 && $noise2D->getGreyValue($x, $y) <= 124) {
                    $city->resources = json_encode(['wool', 'leather']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 125 && $noise2D->getGreyValue($x, $y) <= 149) {
                    $city->resources = json_encode(['timber', 'honey', 'herbs']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 150 && $noise2D->getGreyValue($x, $y) <= 174) {
                    $city->resources = json_encode(['coal']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 175 && $noise2D->getGreyValue($x, $y) <= 199) {
                    $city->resources = json_encode(['stone']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 200 && $noise2D->getGreyValue($x, $y) <= 224) {
                    $city->resources = json_encode(['iron_ore', 'stone']);
                }
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
            while (City::where('x', $city->x)->where('y', $city->y)->first() && $attempts < $max_attempts || $noise2D->getGreyValue($x, $y) <= 49 || $noise2D->getGreyValue($x, $y) >= 225) {
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
                if ($noise2D->getGreyValue($x, $y) >= 50 && $noise2D->getGreyValue($x, $y) <= 74) {
                    $city->resources = json_encode(['fish', 'salt', 'water']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 75 && $noise2D->getGreyValue($x, $y) <= 99) {
                    $city->resources = json_encode(['wheat']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 100 && $noise2D->getGreyValue($x, $y) <= 124) {
                    $city->resources = json_encode(['wool', 'leather']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 125 && $noise2D->getGreyValue($x, $y) <= 149) {
                    $city->resources = json_encode(['timber', 'honey', 'herbs']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 150 && $noise2D->getGreyValue($x, $y) <= 174) {
                    $city->resources = json_encode(['coal']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 175 && $noise2D->getGreyValue($x, $y) <= 199) {
                    $city->resources = json_encode(['stone']);
                } elseif ($noise2D->getGreyValue($x, $y) >= 200 && $noise2D->getGreyValue($x, $y) <= 224) {
                    $city->resources = json_encode(['iron_ore', 'stone']);
                }
                $city->save();
            }
        }
    }
}
