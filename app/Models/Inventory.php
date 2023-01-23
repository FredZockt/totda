<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';
    protected $fillable = ['user_id', 'good_id', 'quantity', 'type', 'max_stack'];
    public $MAX_SLOTS = 32;
    public $MAX_STACK = 100;

    public function player()
    {
        return $this->belongsTo(User::class);
    }

    //$user->id, $good->id, $amount, 'good', $good->max_stack
    public static function addItem($user_id, $good_id, $quantity, $type, $max_stack)
    {
        $slots = Inventory::where('good_id', $good_id)->where('quantity', '<', $max_stack)->get();
        $totalSlots = Inventory::where('user_id', $user_id)->get()->count();

        // loop through the returned collection of slots
        foreach ($slots as $slot) {
            // check if there is still quantity to add
            if ($quantity > 0) {
                // calculate the difference between the current slot quantity and max_stack
                $diff = $slot->max_stack - $slot->quantity;

                // check if the difference is greater than or equal to the remaining quantity
                if ($diff >= $quantity) {
                    // add the remaining quantity to the current slot
                    $slot->quantity += $quantity;
                    // set the remaining quantity to 0
                    $quantity = 0;
                } else {
                    // add the difference to the current slot
                    $slot->quantity += $diff;
                    // subtract the difference from the remaining quantity
                    $quantity -= $diff;
                }
                // save the updated slot
                $slot->save();
            } else {
                // if quantity is 0 or below, break out of the loop
                break;
            }
        }

        if($quantity > 0) {
            if ($quantity > $max_stack) {
                $remainingQuantity = $quantity - $max_stack;
                Inventory::create([
                    'user_id' => $user_id,
                    'good_id' => $good_id,
                    'quantity' => $max_stack,
                    'max_stack' => $max_stack
                ]);
                $totalSlots++;
                while ($remainingQuantity > 0 && $totalSlots < 32) {
                    $newSlot = Inventory::create([
                        'user_id' => $user_id,
                        'good_id' => $good_id,
                        'quantity' => 0,
                        'max_stack' => $max_stack
                    ]);
                    $totalSlots++;
                    if ($remainingQuantity > $max_stack) {
                        $newSlot->update(['quantity' => $max_stack]);
                        $remainingQuantity -= $max_stack;
                    } else {
                        $newSlot->update(['quantity' => $remainingQuantity]);
                        $remainingQuantity = 0;
                    }
                }
            } else {
                if($totalSlots < 32) {
                    Inventory::create([
                        'user_id' => $user_id,
                        'good_id' => $good_id,
                        'quantity' => $quantity,
                        'max_stack' => $max_stack
                    ]);
                }
            }
        }

    }

}
