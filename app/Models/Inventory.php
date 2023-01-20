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

    public static function addItem($user_id, $good_id, $quantity, $type, $max_stack)
    {
        $existing_item = Inventory::where('user_id', $user_id)
            ->where('good_id', $good_id)
            ->first();

        if($existing_item) {
            if($type != 'good') {
                $max_stack = 1;
            }

            if(($existing_item->quantity + $quantity) <= $max_stack) {
                $existing_item->quantity += $quantity;
                $existing_item->save();
            } else {
                $remaining_quantity = ($existing_item->quantity + $quantity) - $max_stack;
                $existing_item->quantity = $max_stack;
                $existing_item->save();
                if(count(Inventory::where('user_id', $user_id)->get()) < 32) {
                    Inventory::create([
                        'user_id' => $user_id,
                        'good_id' => $good_id,
                        'quantity' => $remaining_quantity,
                        'max_stack' => $max_stack
                    ]);
                } else {
                    return 'Inventory is full!';
                }
            }
        } else {
            if(count(Inventory::where('user_id', $user_id)->get()) < 32) {
                Inventory::create([
                    'user_id' => $user_id,
                    'good_id' => $good_id,
                    'quantity' => $quantity,
                    'max_stack' => $max_stack
                ]);
            } else {
                return 'Inventory is full!';
            }
        }
    }
}
