<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Job;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $buildings = Building::all();

        $job = new Job();
        $job->name = 'walking';
        $job->save();

        foreach($buildings as $index => $building) {
            $job = new Job();
            $job->name = strtolower(str_replace(' ', '_', $building->city()->first()->name . '_' . $building->name . '_job'));
            $job->building_id = $building->id;
            $job->save();
        }
    }
}
