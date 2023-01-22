<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Economy extends Model
{
    protected $table = 'economy';
    protected $fillable = ['good_id', 'price', 'quantity'];

    public $base_price;

    // this method handles the sell of a user to economy
    public function handleSell($good_id, $kingdom_id, $quantity)
    {
        $goods = $this->where('good_id', $good_id)->where('kingdom_id', $kingdom_id)->first();
        $this->base_price = $goods->price;

        $goods->quantity += $quantity;
        $goods->price = $this->base_price * exp(-($quantity/1000)/($goods->quantity || 1));
        $goods->price = floor($goods->price * pow(10, 6)) / pow(10, 6);
        if($goods->price < 0.000001) {
            $goods->price = 0.000001;
        }
        $goods->save();
    }

    // this method handles the buy of a user from economy
    public function handleBuy($good_id, $kingdom_id, $quantity)
    {
        $goods = $this->where('good_id', $good_id)->where('kingdom_id', $kingdom_id)->first();
        $this->base_price = $goods->price;

        if($goods->quantity >= $quantity) {
            $goods->quantity -= $quantity;
            $goods->price = $this->base_price * exp(($quantity/1000)/($goods->quantity || 1));
            $goods->save();
        }
    }

    public function good()
    {
        return $this->belongsTo(Good::class);
    }
}
