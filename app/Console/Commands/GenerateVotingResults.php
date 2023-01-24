<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Kingdom;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateVotingResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:voting_results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates vacancies, so users can apply as governor or even as king';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $votings = DB::table('vacancies')->where('city_id', null)->where('kingdom_id', '!=', null)->whereDate('open_until', '<', Carbon::now())->get();

        foreach($votings as $voting) {
            $applicants = DB::table('king_application')
                ->leftJoin('users', 'users.id', '=', 'king_application.user_id')
                ->where('king_application.kingdom_id', $voting->kingdom_id)
                ->select('king_application.*', 'users.*')
                ->get();

            foreach($applicants as $index => $applicant) {
                $applicants[$index]->votings = DB::table('king_voting')->where('kings_applicant_id', $applicant->user_id)->count();
            }

            $newKing = $applicants->sortByDesc('votings')->first();
            Kingdom::where('id', $voting->kingdom_id)->update([
                'king_id' => $newKing->user_id
            ]);

            foreach($applicants as $index => $applicant) {
                DB::table('king_voting')->where('kings_applicant_id', $applicant->user_id)->delete();
            }
            DB::table('king_application')->where('king_application.kingdom_id', $voting->kingdom_id)->delete();

        }


        return Command::SUCCESS;
    }
}
