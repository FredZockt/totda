<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $fillable = ['name', 'kingdom', 'tax_rate', 'x', 'y'];

    public $distanceToInKm = 0;
    public $distanceToInSeconds = 0;
    public $distanceToAsDate;

    public function kingdom()
    {
        return $this->belongsTo(Kingdom::class);
    }

    public function calculateDistance($sourceCityId, $targetCityId)
    {
        $city1 = City::where('id', $sourceCityId)->first();
        $city2 = City::where('id', $targetCityId)->first();

        $x_distance = abs($city1->x - $city2->x);
        $y_distance = abs($city1->y - $city2->y);

        return round(sqrt(pow($x_distance, 2) + pow($y_distance, 2)), 2);
    }

    public function calculateWalktime($kilometers)
    {
        // (km * 10 minutes per km) * 60 seconds = time to walk in seconds
        return ($kilometers * 10) * 60;
    }
}
