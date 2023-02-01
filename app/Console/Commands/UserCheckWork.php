<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UserCheckWork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check_work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if players still have work open. this will free the work places if users gets inactive';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $working_users = User::where('work_finished_at','<', Carbon::now())->get();
        if($working_users) {
            foreach($working_users as $working_user) {
                $working_user->job()->first()->finish($working_user);
            }
        }
        return Command::SUCCESS;
    }
}
