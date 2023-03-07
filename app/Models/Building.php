<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'buildings';
    protected $fillable = ['name', 'short_job', 'mid_job', 'long_job', 'user_id'];

    public function setUserId($user_id, $city_id, $new_user_id)
    {
        $candidate = Building::where('user_id', $user_id)->where('city_id', $city_id)->all();
        $candidate->user_id = $new_user_id;
        $candidate->save();
    }

    public function setOwnerId($user_id, $city_id, $new_user_id)
    {
        $candidate = Building::where('owner_id', $user_id)->where('city_id', $city_id)->all();
        $candidate->owner_id = $new_user_id;

        self::setUserId($user_id, $city_id, $new_user_id);

        $candidate->save();
    }

    public function setAuction($id) {
        $auction = new Auction();
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function good()
    {
        return $this->belongsTo(Good::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id', 'users');
    }
}
