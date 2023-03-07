<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $table = 'auctions';

    protected $fillable = ['building_id', 'user_id', 'bid', 'initiator_id'];
}
