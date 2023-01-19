<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';
    protected $fillable = ['user_id', 'item_id', 'quantity', 'type'];
    public $MAX_SLOTS = 32;
    public $MAX_STACK = 100;

    public function player()
    {
        return $this->belongsTo(User::class);
    }

    public function addItem($user_id, $item_id, $quantity, $type)
    {
        $existing_item = $this->where('user_id', $user_id)
            ->where('item_id', $item_id)
            ->where('type', $type)
            ->first();

        if($existing_item) {
            if($type == 'good') {
                $max_stack = $this->MAX_STACK;
            } else {
                $max_stack = 1;
            }

            if(($existing_item->quantity + $quantity) <= $max_stack) {
                $existing_item->quantity += $quantity;
                $existing_item->save();
            } else {
                $remaining_quantity = ($existing_item->quantity + $quantity) - $max_stack;
                $existing_item->quantity = $max_stack;
                $existing_item->save();
                if(count($this->where('user_id', $user_id)->get()) < $this->MAX_SLOTS) {
                    $this->create([
                        'user_id' => $user_id,
                        'item_id' => $item_id,
                        'quantity' => $remaining_quantity,
                        'type' => $type
                    ]);
                } else {
                    return 'Inventory is full!';
                }
            }
        } else {
            if(count($this->where('user_id', $user_id)->get()) < $this->MAX_SLOTS) {
                $this->create([
                    'user_id' => $user_id,
                    'item_id' => $item_id,
                    'quantity' => $quantity,
                    'type' => $type
                ]);
            } else {
                return 'Inventory is full!';
            }
        }
    }
}
