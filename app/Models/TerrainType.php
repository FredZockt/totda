<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerrainType extends Model
{
    use HasFactory;

    protected $fillable = [
        'terrain_type', 'resources'
    ];
}
