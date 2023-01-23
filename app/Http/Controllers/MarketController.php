<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Economy;
use App\Models\Good;
use App\Models\Inventory;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = auth()->user();
        $city = $user->currentCity()->first();
        $goods = Economy::where('kingdom_id', $city->kingdom()->first()->id)->get();

        $walkFlag = false;

        if($user->job_id == 1) {
            $walkFlag = true;
        }

        foreach($goods as $index => $good)
        {
            $src = $good->good()->first();
            $good->name = $src->name;
            $good->max_stack = $src->max_stack;
        }

        return view('market.index', [
            'city' => $city,
            'walkFlag' => $walkFlag,
            'goods' => $goods,
            'available_gold' => $user->gold
        ]);
    }

    public function buy(Request $request, $id)
    {
        $user = auth()->user();
        $city = $user->currentCity()->first();
        $kingdom = $city->kingdom()->first();
        $good = Economy::where('id', $id)->where('kingdom_id', $kingdom->id)->first();
        $price = $good->price;
        $quantity = $request->input('quantity');

        if($good->quantity < $quantity) {
            return redirect()->back()->with([
                'status' => 'Something went wrong, please try again',
                'status_type' => 'danger'
            ]);
        }

        // is user walking?
        if($user->job_id && $user->job_id == 1) {
            return redirect()->back()->with([
                'status' => 'You are currently walking. Therefore you cannot buy yet.',
                'status_type' => 'danger'
            ]);
        }

        if (!$good) {
            return redirect()->back()->with([
                'status' => 'Item not found',
                'status_type' => 'danger'
            ]);
        }

        $tax = ($price * $quantity) * $city->tax_rate;

        $user->gold -= ($price * $quantity) + $tax;

        if($user->gold < 0) {
            return redirect()->back()->with([
                'status' => 'Not enough gold',
                'status_type' => 'danger'
            ]);
        } else {
            $user->save();
        }

        $kingdom->gold += $tax;
        $kingdom->save();

        // update economy
        $good->handleBuy($good->good_id, $kingdom->id, $quantity);
        Inventory::addItem($user->id, $good->good_id, $quantity, 'good', $good->good()->first()->max_stack);

        return redirect()->back()->with([
            'status' => 'Item bought successfully for: ' . (($price * $quantity) + $tax) . '! Tax: ' . $tax,
            'status_type' => 'success'
        ]);
    }
}
