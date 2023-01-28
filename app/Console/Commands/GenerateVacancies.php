<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Kingdom;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateVacancies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:vacancies';

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
        $cities = City::all();
        $kingdoms = Kingdom::all();

        foreach($cities as $city) {
            if(!$city->governor_id) {
                if(!DB::table('vacancies')->where('city_id', $city->id)->where('kingdom_id', $city->kingdom_id)->first()) {
                    DB::table('vacancies')->insert([
                        'city_id' => $city->id,
                        'kingdom_id' => $city->kingdom_id,
                        'open_until' => Carbon::now()->addDays(2),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                } else {
                    DB::table('vacancies')->where('city_id', $city->id)->where('kingdom_id', $city->kingdom_id)->update([
                        'open_until' => Carbon::now()->addDays(2),
                        'updated_at' => Carbon::now()
                    ]);
                }
            } else {
                if(DB::table('vacancies')->where('city_id', $city->id)->where('kingdom_id', $city->kingdom_id)->first()) {
                    DB::table('vacancies')->where('city_id', $city->id)->where('kingdom_id', $city->kingdom_id)->delete();
                }
            }
        }

        foreach($kingdoms as $kingdom) {
            if(!$kingdom->king_id) {
                if(!DB::table('vacancies')->where('city_id', null)->where('kingdom_id', $kingdom->id)->first()) {
                    DB::table('vacancies')->insert([
                        'city_id' => null,
                        'kingdom_id' => $kingdom->id,
                        'open_until' => Carbon::now()->addDays(2),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                } else {
                    DB::table('vacancies')->where('city_id', null)->where('kingdom_id', $kingdom->id)->update([
                        'open_until' => Carbon::now()->addDays(2),
                        'updated_at' => Carbon::now()
                    ]);
                }
            } else {
                if(DB::table('vacancies')->where('city_id', null)->where('kingdom_id', $kingdom->id)->first()) {
                    DB::table('vacancies')->where('city_id', null)->where('kingdom_id', $kingdom->id)->delete();
                }
            }
        }

        return Command::SUCCESS;
    }
}
