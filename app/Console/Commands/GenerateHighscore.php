<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class GenerateHighscore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:highscore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the highscore at 4:00';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $players = User::orderBy('gold', 'desc')->take(100)->get();
        Cache::put('highscore', $players, 1440);
    }
}
