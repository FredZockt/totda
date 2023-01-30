<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Good as Good;

class GoodsTableSeeder extends Seeder
{
    public function run()
    {
        $goods = [
            ['name' => 'wheat', 'price' => 0.5, 'max_stack' => 100, 'quantity' => 0],
            ['name' => 'timber', 'price' => 1.5, 'max_stack' => 50, 'quantity' => 0],
            ['name' => 'wool', 'price' => 2, 'max_stack' => 100, 'quantity' => 0],
            ['name' => 'iron_ore', 'price' => 5, 'max_stack' => 50, 'quantity' => 0],
            ['name' => 'leather', 'price' => 3, 'max_stack' => 100, 'quantity' => 0],
            ['name' => 'fish', 'price' => 2, 'max_stack' => 100, 'quantity' => 0],
            ['name' => 'salt', 'price' => 4, 'max_stack' => 50, 'quantity' => 0],
            ['name' => 'herbs', 'price' => 2, 'max_stack' => 100, 'quantity' => 0],
            ['name' => 'coal', 'price' => 6, 'max_stack' => 50, 'quantity' => 0],
            ['name' => 'honey', 'price' => 3, 'max_stack' => 100, 'quantity' => 0],
            ['name' => 'stone', 'price' => 4, 'max_stack' => 50, 'quantity' => 0],
            ['name' => 'water', 'price' => 0.2, 'max_stack' => 100, 'quantity' => 0],
        ];

        foreach ($goods as $good) {
            Good::create($good);
        }
    }
}
