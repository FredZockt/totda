<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'buildings';
    protected $fillable = ['name', 'short_job', 'mid_job', 'long_job', 'user_id'];

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
}
