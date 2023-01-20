<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(KingdomsTableSeeder::class);
        $this->call(GoodsTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(BuildingsTableSeeder::class);
        $this->call(EconomyTableSeeder::class);
        $this->call(InventoryTableSeeder::class);
    }
}
