<?php

namespace App\Console\Commands;

use App\Models\City;
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
        foreach ($kingdoms as $kingdom) {
            $tax = 0;
            $cities = $kingdom->cities()->get();
            foreach ($cities as $city) {
                $city_tax = round($city->gold / 100 * $city->tax_rate_kingdom, 6);
                $tax += $city_tax;
                $city->gold -= $city_tax;
            }
            $kingdom->gold += $tax;
            City::saveMany($cities);
        }
        // Save the changes to the database after all the calculations are done
        Kingdom::saveMany($kingdoms);

        return Command::SUCCESS;
    }
}
