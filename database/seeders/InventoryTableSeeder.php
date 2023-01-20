<?php

namespace Database\Seeders;

use App\Models\Good;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $goods = Good::all();

        foreach($users as $user) {
            foreach ($goods as $good) {
                $slot = new Inventory();
                $slot->user_id = $user->id;
                $slot->good_id = $good->id;
                $slot->quantity = rand(1, $good->max_stack);
                $slot->max_stack = $good->max_stack;
                $slot->save();
            }
        }
    }
}
