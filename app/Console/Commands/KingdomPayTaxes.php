<?php

namespace App\Console\Commands;

use App\Models\Kingdom;
use Illuminate\Console\Command;

class KingdomPayTaxes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kingdom:pay_taxes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to retrieve taxes from kingdom specific cities';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $kingdoms = Kingdom::all();
        $kingdom_cities = [];

        foreach($kingdoms as $kingdom) {
            $kingdom_cities[$kingdom->id] = $kingdom->cities()->get();
        }

        foreach($kingdoms as $kingdom) {
            $tax = 0;
            foreach($kingdom_cities as $cities) {
                foreach($cities as $city) {
                    if($city->kingdom_id == $kingdom->id) {
                        $city_tax = round($city->gold / 100 * $city->tax_rate_kingdom, 6);
                        $tax += $city_tax;
                        $city->gold -= $city_tax;
                        $city->save();
                    }
                }
            }
            $kingdom->gold += $tax;
            $kingdom->save();
        }

        return Command::SUCCESS;
    }
}
