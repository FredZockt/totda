<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $fillable = ['name', 'kingdom', 'tax_rate', 'x', 'y'];

    public $distanceToInKm = 0;
    public $distanceToInSeconds = 0;
    public $distanceToAsReadable;
    public $distanceToAsDate;

    public function kingdom()
    {
        return $this->belongsTo(Kingdom::class);
    }

    public function mayor()
    {
        return $this->belongsTo(User::class, 'mayor_id');
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
    
    public function getReadableWalktime($seconds)
    {
        $suffix = ':';
        $days = floor(($seconds / (24*60*60)));
        $hours = ($seconds / (60*60)) % 24;
        $minutes = ($seconds / 60) % 60;
        $seconds = ($seconds / 1) % 60;

        if($days > 0)
        {
            $days =  $days < 10 ? '0' . $days . " d, " : $days ." d, ";
        } else {
            $days = '';
        }

        $hours = $hours < 10 ? '0' . $hours . $suffix : $hours . $suffix;
        $minutes = $minutes < 10 ? '0' . $minutes . $suffix : $minutes . $suffix;
        $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

        return $days . $hours . $minutes . $seconds;
    }
}
