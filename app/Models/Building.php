<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'buildings';
    protected $fillable = ['name', 'short_job', 'mid_job', 'long_job'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
