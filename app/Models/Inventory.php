<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';
    protected $fillable = ['user_id', 'good_id', 'quantity', 'type'];
    public $MAX_SLOTS = 32;
    public $MAX_STACK = 100;

    public function player()
    {
        return $this->belongsTo(User::class);
    }

    //$user->id, $good->id, $amount, 'good', $good->max_stack
    public static function addItem($user_id, $good_id, $quantity, $type, $max_stack)
    {
        $slot = Inventory::where('good_id', $good_id)->where('quantity', '<', $max_stack)->first();
        $totalSlots = Inventory::where('user_id', $user_id)->get()->count();

        if ($slot) {
            if ($quantity + $slot->quantity > $max_stack) {
                $remainingQuantity = $quantity + $slot->quantity - $max_stack;
                $slot->update(['quantity' => $max_stack]);
                while ($remainingQuantity > 0 && $totalSlots < 32) {
                    $newSlot = Inventory::create([
                        'user_id' => $user_id,
                        'good_id' => $good_id,
                        'quantity' => 0,
                        'max_Stack' => $max_stack
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
                $slot->update(['quantity' => $slot->quantity + $quantity]);
            }
        } else {
            if ($quantity > $max_stack) {
                $remainingQuantity = $quantity - $max_stack;
                Inventory::create([
                    'user_id' => $user_id,
                    'good_id' => $good_id,
                    'quantity' => $max_stack,
                    'max_Stack' => $max_stack
                ]);
                $totalSlots++;
                while ($remainingQuantity > 0 && $totalSlots < 32) {
                    $newSlot = Inventory::create([
                        'user_id' => $user_id,
                        'good_id' => $good_id,
                        'quantity' => 0,
                        'max_Stack' => $max_stack
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
                        'max_Stack' => $max_stack
                    ]);
                }
            }
        }
    }

}
