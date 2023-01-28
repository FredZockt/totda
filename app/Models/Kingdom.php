<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kingdom extends Model
{
    use HasFactory;

    protected $table = 'kingdoms';
    protected $fillable = ['name', 'gold', 'king_id'];

    public function cities()
    {
        return $this->hasMany(City::class, 'kingdom_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'kingdom_id');
    }

    public function king()
    {
        return $this->belongsTo(User::class, 'king_id');
    }

    public static function saveMany($instances)
    {
        foreach ($instances as $instance) {
            $instance->save();
        }
    }
}
