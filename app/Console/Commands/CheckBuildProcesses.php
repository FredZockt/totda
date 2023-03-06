<?php

namespace App\Console\Commands;

use App\Models\Building;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckBuildProcesses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'building:check_build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Looks up the database for constructions that could be set to active';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $buildings = Building::where('created_at','<', Carbon::now()->subHours(8))->where('active', 0)->get();

        foreach($buildings as $building) {
            $building->active = true;
            $building->save();
        }

        return Command::SUCCESS;
    }
}
