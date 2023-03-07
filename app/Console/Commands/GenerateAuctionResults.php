<?php

namespace App\Console\Commands;

use App\Models\Auction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateAuctionResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:auction_results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'calculates the auction results';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $auctions = Auction::where('created_at','<', Carbon::now()->subDays(2))->get();
        if($auctions->count() > 0) {
            foreach($auctions as $auction) {
                $user = \App\Models\User::find($auction->user_id);
                $building = \App\Models\Building::find($auction->building_id);

                $user->gold -= $auction->bid;
                $user->save();

                if($auction->initiator_id == $building->user_id) {
                    $building->owner_id = $auction->user_id;
                    $initiator = User::find($auction->initiator_id);
                    $initiator->gold += $auction->bid;
                    $initiator->save();
                }

                $building->user_id = $auction->user_id;
                $building->save();


                $auction->delete();
            }
        }

        return Command::SUCCESS;
    }
}
