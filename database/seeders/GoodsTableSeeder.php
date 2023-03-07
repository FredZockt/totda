<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Good as Good;

class GoodsTableSeeder extends Seeder
{
    public function run()
    {
        $goods = [
            ['name' => 'wheat', 'price' => 0.5, 'max_stack' => 100],
            ['name' => 'timber', 'price' => 1.5, 'max_stack' => 50],
            ['name' => 'wool', 'price' => 2, 'max_stack' => 100],
            ['name' => 'iron_ore', 'price' => 5, 'max_stack' => 50],
            ['name' => 'leather', 'price' => 3, 'max_stack' => 100],
            ['name' => 'fish', 'price' => 2, 'max_stack' => 100],
            ['name' => 'salt', 'price' => 4, 'max_stack' => 50],
            ['name' => 'herbs', 'price' => 2, 'max_stack' => 100],
            ['name' => 'coal', 'price' => 6, 'max_stack' => 50],
            ['name' => 'honey', 'price' => 3, 'max_stack' => 100],
            ['name' => 'stone', 'price' => 4, 'max_stack' => 50],
            ['name' => 'water', 'price' => 0.2, 'max_stack' => 100],
        ];

        foreach ($goods as $good) {
            Good::create($good);
        }
    }
}
