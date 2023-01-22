<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kingdom extends Model
{
    use HasFactory;

    protected $table = 'kingdoms';
    protected $fillable = ['name', 'gold'];

    public function cities()
    {
        return $this->hasMany(City::class, 'kingdom_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'kingdom_id');
    }
}
