<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
        $schedule->command('generate:highscore')->dailyAt('4:00');
        $schedule->command('generate:vacancies')->twiceDailyAt(0, 12);
        $schedule->command('generate:voting_results')->twiceDailyAt(0, 12);
        $schedule->command('kingdom:pay_taxes')->dailyAt('0:00');
        $schedule->command('user:check_work')->everyTenMinutes();
        */
        // just for development
        $schedule->command('generate:highscore')->everyMinute();
        $schedule->command('generate:vacancies')->everyMinute();
        $schedule->command('generate:voting_results')->everyMinute();
        $schedule->command('generate:auction_results')->everyMinute();
        $schedule->command('kingdom:pay_taxes')->everyMinute();
        $schedule->command('user:check_work')->everyMinute();
        $schedule->command('building:check_build')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
