<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Economy extends Model
{
    protected $table = 'economy';
    protected $fillable = ['goods_id', 'price', 'quantity'];

    public $base_price;

    public function handleSell($goods_id, $quantity, $user_id)
    {
        $goods = $this->where('good_id', $goods_id)->first();
        $this->base_price = $goods->price;

        if($goods->quantity >= $quantity) {
            $goods->quantity -= $quantity;
            $goods->price = $this->base_price * exp(-$quantity/$goods->stock);
            $goods->save();

            $user = User::find($user_id);
            $user->gold += $goods->price * $quantity;
            $user->save();
        }
    }

    public function handleBuy($goods_id, $quantity, $user_id)
    {
        $goods = $this->where('good_id', $goods_id)->first();
        $this->base_price = $goods->price;
        $goods->quantity += $quantity;
        $goods->price = $this->base_price * exp($quantity/$goods->stock);
        $goods->save();

        $user = User::find($user_id);
        $user->gold -= $goods->price * $quantity;
        $user->save();
    }
}
