<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Economy;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // get the authenticated user's ID
        $user = auth()->user();

        // left join the slots table with the goods table
        $slots = DB::table('inventories')
            ->leftJoin('goods', 'inventories.good_id', '=', 'goods.id')
            ->leftJoin('economy', function($join) use ($user) {
                $join->on('economy.good_id', '=', 'goods.id')
                    ->where('economy.kingdom_id', '=', $user->currentCity()->first()->kingdom_id);
            })
            ->select('inventories.*', 'goods.name as good_name', 'goods.max_stack as max_stack',
                DB::raw("IFNULL(economy.price, goods.price) as price"),
                DB::raw("goods.price as base_price"))
            ->where('user_id', $user->id)
            ->get();

        return view('inventory.index', compact('slots'));
    }

    public function delete($id)
    {
        $item = Inventory::find($id);
        if (!$item) {
            return redirect()->route('inventory.index')->with('status_type', 'warning')->with('status', 'Item not found');
        }
        if (auth()->user()->id !== $item->user_id) {
            return redirect()->route('inventory.index')->with('status_type', 'warning')->with('status', 'You are not the owner of this item');
        }

        $item->delete();

        return redirect()->route('inventory.index')->with('status_type', 'success')->with('status', 'Item deleted successfully');
    }

    public function sell(Request $request, $id)
    {
        // is user walking?
        if(auth()->user()->job_id && auth()->user()->job_id == 1) {
            return redirect()->back()->with([
                'status' => 'You are currently walking. Therefore you cannot sell anything.',
                'status_type' => 'danger'
            ]);
        }

        $item = Inventory::find($id);
        if (!$item) {
            return redirect()->back()->with([
                'status' => 'Item not found',
                'status_type' => 'danger'
            ]);
        }

        if (auth()->user()->id != $item->user_id) {
            return redirect()->back()->with([
                'status' => 'You do not have permission to sell this item',
                'status_type' => 'danger'
            ]);
        }

        $quantity = $request->input('quantity');
        if ($quantity > $item->quantity) {
            return redirect()->back()->with([
                'status' => 'You do not have enough quantity of this item to sell',
                'status_type' => 'danger'
            ]);
        }

        $user = Auth::user();
        $city = $user->currentCity()->first();
        $kingdom = $city->kingdom()->first();

        $good = Economy::where('good_id', $item->good_id)->where('kingdom_id', $kingdom->id)->first();
        $price = $good->price;

        $item->quantity -= $quantity;
        if($item->quantity <= 0) {
            $item->delete();
        } else {
            $item->save();
        }

        $tax = ($price * $quantity) * $city->tax_rate;

        $user->gold += ($price * $quantity) - $tax;
        $user->save();

        $kingdom->gold += $tax;
        $kingdom->save();

        // update economy
        $good->handleSell($item->good_id, $kingdom->id, $quantity);

        return redirect()->back()->with([
            'status' => 'Item sold successfully for: ' . ($price * $quantity) - $tax . '! Tax: ' . $tax,
            'status_type' => 'success'
        ]);
    }

}
