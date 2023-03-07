<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Building;
use App\Models\City;
use App\Models\Good;
use App\Models\Job;
use App\Models\Militia;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $city = $user->currentCity()->first();
        $systemBuildings = Building::leftJoin('goods', 'goods.id', '=', 'buildings.good_id')->where('system', true)->where('city_id', $city->id)->get([
            'goods.name as good_name',
            'buildings.*'
        ]);
        $userBuildings = Building::leftJoin('goods', 'goods.id', '=', 'buildings.good_id')->where('system', false)->where('city_id', $city->id)->get([
            'goods.name as good_name',
            'buildings.*'
        ]);
        $workFlag = !!$user->job_id;
        $walkFlag = false;
        $auctions = [];
        $governor = $city->governor()->first();
        $application = DB::table('governor_application')->where('user_id', $user->id)->first();
        $applicationK = DB::table('king_application')->where('user_id', $user->id)->first();
        $vacancy = DB::table('vacancies')->where('city_id', $city->id)->first();
        $canBuild = true;
        $potentialResourceBuildings = [];
        $units = [];
        $militias = [];

        if($userBuildings->count() < $city->level * 5 && $user->gold > 25000) {
            if($city->governor()->first()) {
                if($city->governor()->first()->id == $user->id) {
                    $canBuild = false;
                }
            }
            if($city->kingdom()->first()->king()->first()) {
                if($city->kingdom()->first()->king()->first()->id == $user->id) {
                    $canBuild = false;
                }
            }
            if($applicationK || $application) {
                $canBuild = false;
            }
        } else {
            $canBuild = false;
        }

        if($vacancy) {
            if(Carbon::createFromTimeString($vacancy->open_until)->timestamp <=  Carbon::now()->timestamp) {
                $vacancy = null;
            }
        }

        if($user->job_id == 1) {
            $walkFlag = true;
        }
        foreach($systemBuildings as $building) {
            $building->short_job = $city->getReadableWalktime($building->short_job);
            $building->mid_job = $city->getReadableWalktime($building->mid_job);
            $building->long_job = $city->getReadableWalktime($building->long_job);

            if($canBuild) {
                $potentialResourceBuildings[] = Good::find($building->good_id);
            }
        }

        $userBuildingIds = [];

        foreach($userBuildings as $index => $building) {
            $building->short_job = $city->getReadableWalktime($building->short_job);
            $building->mid_job = $city->getReadableWalktime($building->mid_job);
            $building->long_job = $city->getReadableWalktime($building->long_job);

            $userBuildingIds[] = $building->id;
            if($building->user_id == $user->id) {
                $canBuild = false;
            }
        }

        if($city->governor_id == $user->id) {
            $units = Unit::all();
            $militias = Militia::where('city_id', $city->id)->get();
        }


        if(count($userBuildingIds) > 0) {
            $auctions = Auction::leftJoin('buildings', 'buildings.id', 'auctions.building_id')
                ->whereIn('building_id', $userBuildingIds)
                ->leftJoin('goods', 'buildings.good_id', 'goods.id')
                ->leftJoin('users', 'buildings.owner_id', 'users.id')
                ->get([
                    'auctions.*',
                    'buildings.level as building_level',
                    'buildings.name as building_name',
                    'buildings.user_id as building_user_id',
                    'goods.name as good_name',
                    'users.name as owner_name'
                ]);
        }

        return view('city.index', [
            'systemBuildings' => $systemBuildings,
            'userBuildings' => $userBuildings,
            'city' => $city,
            'workFlag' => $workFlag,
            'walkFlag' => $walkFlag,
            'governor' => $governor,
            'user' => $user,
            'application' => $application,
            'vacancy' => $vacancy,
            'canBuild' => $canBuild,
            'potentialResourceBuildings' => $potentialResourceBuildings,
            'auctions' => $auctions,
            'units' => $units,
            'militias' => $militias,
        ]);
    }

    public function apply()
    {
        $user = auth()->user()->first();
        $city = $user->currentCity()->first();
        $kingdom = $city->kingdom()->first();
        $application = DB::table('governor_application');

        if($user->kingdom_id != $city->kingdom_id) {
            return redirect()->back()->with([
                'status' => 'You cannot apply here.',
                'status_type' => 'danger'
            ]);
        }

        if($application->where('user_id', $user->id)->first() != null) {
            return redirect()->back()->with([
                'status' => 'You already applied as governor.',
                'status_type' => 'danger'
            ]);
        }

        if($user->gold > 500) {
            // user has to pay a fee
            $user->gold -= 500;
            $user->save();

            // kingdom gets the fee
            $kingdom->gold += 500;
            $kingdom->save();

            // save applicant
            $application->insert([
                'user_id' => $user->id,
                'city_id' => $city->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

        } else {
            return redirect()->back()->with([
                'status' => 'You need 500 gold to apply',
                'status_type' => 'danger'
            ]);
        }

        return redirect()->back()->with([
            'status' => 'You successfully applied as governor!',
            'status_type' => 'success'
        ]);
    }

    public function cancel()
    {
        $user = auth()->user()->first();
        $city = $user->currentCity()->first();
        $application = DB::table('governor_application')->where('user_id', $user->id)->where('city_id', $city->id);

        if($application->first() == null) {
            return redirect()->back()->with([
                'status' => 'There is nothing to withdraw.',
                'status_type' => 'danger'
            ]);
        }

        $application->delete();
        return redirect()->back()->with([
            'status' => 'Application withdrawn.',
            'status_type' => 'warning'
        ]);
    }

    public function depose($city_id)
    {
        $city = City::find($city_id);
        $kingdom = $city->kingdom()->first();

        if($kingdom->king_id != auth()->user()->id) {
            return redirect()->back()->with([
                'status' => 'Not allowed.',
                'status_type' => 'danger'
            ]);
        }

        $city->governor_id = null;
        $city->save();

        return redirect()->back()->with([
            'status' => 'You deposed the governor of ' . $city->name,
            'status_type' => 'success'
        ]);
    }

    public function appoint($city_id, $user_id)
    {
        $city = City::find($city_id);
        $kingdom = $city->kingdom()->first();

        if($kingdom->king_id != auth()->user()->id) {
            return redirect()->back()->with([
                'status' => 'Not allowed.',
                'status_type' => 'danger'
            ]);
        }

        $city->governor_id = $user_id;
        $city->save();

        DB::table('governor_application')->where('city_id', $city_id)->delete();

        return redirect()->back()->with([
            'status' => 'You appointed the governor of ' . $city->name,
            'status_type' => 'success'
        ]);
    }

    public function tax(Request $request)
    {
        $rate = $request->input('rate');
        $user = auth()->user()->first();
        $city = $user->currentCity()->first();

        if($user->id != $city->governor()->first()->id) {
            return redirect()->back()->with([
                'status' => 'Not allowed.',
                'status_type' => 'danger'
            ]);
        }

        if($rate < 0.01 || $rate > 0.50) {
            return redirect()->back()->with([
                'status' => 'Not allowed value',
                'status_type' => 'danger'
            ]);
        }

        $city->tax_rate = $rate;
        $city->save();

        return redirect()->back()->with([
            'status' => 'Tax rate adjusted',
            'status_type' => 'success'
        ]);
    }

    public function abdicate()
    {
        $user = auth()->user()->first();
        $city = City::where('governor_id', $user->id)->first();

        if(!$city) {
            return redirect()->back()->with([
                'status' => 'You are no governor',
                'status_type' => 'danger'
            ]);
        }

        $city->governor_id = null;
        $city->save();

        return redirect()->back()->with([
            'status' => 'You have abdicated',
            'status_type' => 'success'
        ]);
    }

    public function build(Request $request)
    {
        $user = auth()->user();
        $city = $user->currentCity()->first();
        $systemBuildings = Building::leftJoin('goods', 'goods.id', '=', 'buildings.good_id')->where('city_id', $city->id)->where('user_id', null)->get([
            'goods.name as good_name',
            'buildings.*'
        ]);
        $userBuildings = Building::leftJoin('goods', 'goods.id', '=', 'buildings.good_id')->where('city_id', $city->id)->where('user_id', '>', 0)->get([
            'goods.name as good_name',
            'buildings.*'
        ]);
        $workFlag = !!$user->job_id;
        $walkFlag = false;
        $application = DB::table('governor_application')->where('user_id', $user->id)->first();
        $applicationK = DB::table('king_application')->where('user_id', $user->id)->first();
        $vacancy = DB::table('vacancies')->where('city_id', $city->id)->first();
        $canBuild = true;
        $potentialResourceBuildings = [];

        if($userBuildings->count() < $city->level * 5 && $user->gold > 25000) {
            if($city->governor()->first()) {
                if($city->governor()->first()->id == $user->id) {
                    $canBuild = false;
                }
            }
            if($city->kingdom()->first()->king()->first()) {
                if($city->kingdom()->first()->king()->first()->id == $user->id) {
                    $canBuild = false;
                }
            }
            if($applicationK || $application) {
                $canBuild = false;
            }
        } else {
            $canBuild = false;
        }

        if($vacancy) {
            if(Carbon::createFromTimeString($vacancy->open_until)->timestamp <=  Carbon::now()->timestamp) {
                $vacancy = null;
            }
        }

        if($user->job_id == 1) {
            $walkFlag = true;
        }

        foreach($systemBuildings as $building) {
            $building->short_job = $city->getReadableWalktime($building->short_job);
            $building->mid_job = $city->getReadableWalktime($building->mid_job);
            $building->long_job = $city->getReadableWalktime($building->long_job);

            if($canBuild) {
                $potentialResourceBuildings[] = $building->good_id;
            }
        }

        foreach($userBuildings as $building) {
            if($building->user_id == $user->id) {
                $canBuild = false;
            }
        }


        $type = $request->input('resource_type');

        if(is_numeric($type)) {
            if($canBuild && !$walkFlag && !$workFlag && in_array((int)$type, $potentialResourceBuildings)) {
                foreach($systemBuildings as $building) {
                    if($building->good_id == (int)$type) {
                        $build = new Building();
                        $build->name = $building->good()->first()->name . '_factory';
                        $build->good_id = (int)$type;
                        $build->city_id = $city->id;
                        $build->active = false;
                        $price = $building->good()->first()->price;
                        $build->short_job = ceil((rand(300, 600) * (1 + $price/100)) / 300) * 300;
                        $build->mid_job = ceil((rand(3600, 7200) * (1 + $price/100)) / 3600) * 3600;
                        $build->long_job = ceil((rand(14400, 28800) * (1 + $price/100)) / 14400) * 14400;
                        $build->owner_id = $user->id;
                        $build->user_id = $user->id;
                        $build->save();

                        for($i = 1; $i <= 3; $i++) {
                            $job = new Job();
                            $job->name = strtolower(str_replace(' ', '_', $building->city()->first()->name . '_' . $building->name . '_job'));
                            $job->building_id = Building::where('city_id', $city->id)->where('user_id', $user->id)->first()->id;
                            $job->task = $i;
                            $job->save();
                        }

                        $user->gold -= 25000;
                        $user->save();
                    }
                }
                return redirect()->back()->with([
                    'status' => 'construction started',
                    'status_type' => 'success'
                ]);
            }
        }

        return redirect()->back()->with([
            'status' => 'something went wrong...',
            'status_type' => 'danger'
        ]);
    }

    public function sellBuilding($building_id)
    {
        if(!is_numeric($building_id)) {
            return redirect()->back()->with([
                'status' => 'something went wrong...',
                'status_type' => 'danger'
            ]);
        }

        $user_id = Auth::id();
        $building = Building::find($building_id);
        $running_auction = Auction::where('building_id', $building->id)->first();

        if($running_auction) {
            return redirect()->back()->with([
                'status' => 'already enrolled for auction...',
                'status_type' => 'danger'
            ]);
        }

        if(!$building || $building->user_id != $user_id) {
            return redirect()->back()->with([
                'status' => 'something went wrong...',
                'status_type' => 'danger'
            ]);
        }

        $auction = new Auction();
        $auction->building_id = $building->id;
        $auction->initiator_id = $user_id;
        $auction->save();

        return redirect()->back()->with([
            'status' => 'You released your building for auction',
            'status_type' => 'success'
        ]);

    }

    public function placeBid(Request $request)
    {
        $user = Auth::user();

        // must be numeric
        $validator = Validator::make($request->all(), [
            'auction' => 'required|integer',
            'bid' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'something went wrong',
                'status_type' => 'danger'
            ]);
        }

        $reserved_gold = $user->reservedGold();

        $auction = Auction::find($validator->getData()['auction']);
        $bid = $validator->getData()['bid'];

        // must be higher by 500 as current bid
        if($auction->bid + 500 > $bid) {
            return redirect()->back()->with([
                'status' => 'your bid must be at least 500 gold higher',
                'status_type' => 'danger'
            ]);
        }

        if($auction->user_id == $user->id) {
            return redirect()->back()->with([
                'status' => 'you are already the highest bidder',
                'status_type' => 'warning'
            ]);
        }

        // must have enough gold,
        if($reserved_gold + $bid > $user->gold) {
            return redirect()->back()->with([
                'status' => 'you dont have enough gold available (reserved from other auctions: ' . number_format($reserved_gold, 0, ',', '.') . ')',
                'status_type' => 'danger'
            ]);
        }

        // not allowed to be walking
        if($user->job_id == 1) {
            return redirect()->back()->with([
                'status' => 'you cannot bid while you walk',
                'status_type' => 'danger'
            ]);
        }

        // must be in the same city,
        if($user->current_city_id != Building::find($auction->building_id)->city_id) {
            return redirect()->back()->with([
                'status' => 'you are not in the city',
                'status_type' => 'danger'
            ]);
        }

        // must be in the same city,
        if($user->id == $auction->initiator_id) {
            return redirect()->back()->with([
                'status' => 'you cannot bid on your own auction',
                'status_type' => 'danger'
            ]);
        }

        // todo: if war, must be same kingdom

        // place bid
        $auction->bid = $bid;
        $auction->user_id = $user->id;
        $auction->save();

        return redirect()->back()->with([
            'status' => 'you placed your bid',
            'status_type' => 'success'
        ]);

    }

    public function levelUp($building_id)
    {
        $user = Auth::user();

        if(!is_numeric($building_id)) {
            return redirect()->back()->with([
                'status' => 'something went wrong...',
                'status_type' => 'danger'
            ]);
        }

        $user_id = Auth::id();
        $building = Building::find($building_id);
        $running_auction = Auction::where('building_id', $building->id)->first();

        if($running_auction) {
            return redirect()->back()->with([
                'status' => 'already enrolled for auction...',
                'status_type' => 'danger'
            ]);
        }

        if(!$building || $building->user_id != $user_id) {
            return redirect()->back()->with([
                'status' => 'this is not yours...',
                'status_type' => 'danger'
            ]);
        }

        if($user->gold >= $building->level * 25000) {
            $user->gold -= $building->level * 25000;
            $user->save();

            $building->created_at = Carbon::now();
            $building->level += 1;
            $building->active = false;
            $building->save();

            return redirect()->back()->with([
                'status' => 'Level up started. it will be finished in 8 hours.',
                'status_type' => 'success'
            ]);
        } else {
            return redirect()->back()->with([
                'status' => 'you can not afford this...',
                'status_type' => 'danger'
            ]);
        }
    }

    public function hire(Request $request) {
        $user = auth()->user()->first();
        $city = $user->currentCity()->first();

        if($city->governor_id != $user->id) {
            return redirect()->back()->with([
                'status' => 'something went wrong',
                'status_type' => 'danger'
            ]);
        }

        $validated = Validator::make($request->all(), [
            'quantity' => 'required|integer',
            'type' => 'required|integer'
        ]);

        if($validated->fails()) {
            return redirect()->back()->with([
                'status' => 'something went wrong',
                'status_type' => 'danger'
            ]);
        }

        $unit = Unit::where('id', $validated->getData()['type'])->first();
        $amount = floor($validated->getData()['quantity']);

        if(!$unit) {
            return redirect()->back()->with([
                'status' => 'something went wrong',
                'status_type' => 'danger'
            ]);
        }

        if($unit->cost * $amount > $city->gold) {
            return redirect()->back()->with([
                'status' => 'something went wrong',
                'status_type' => 'danger'
            ]);
        }

        $troop = Militia::where('city_id', $city->id)->where('unit_id', $unit->id)->first();
        $troop->amount += $amount;
        $troop->save();

        $city->gold -= $unit->cost * $amount;
        $city->save();

        return redirect()->back()->with([
            'status' => 'You hired ' . $amount . ' of ' . $unit->name,
            'status_type' => 'success'
        ]);

    }
}
