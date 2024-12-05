<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait CalculatesDistance
{
    public function CalculateDistance($location1 ,  $location2)
    {
        $latitude1 = $location1['latitude'];
        $longitude1 = $location1['longitude'];

        $latitude2 = $location2['latitude'];
        $longitude2 = $location2['longitude'];

        $earthRadius = 6371;
        $latDiff = deg2rad($latitude2 - $latitude1);
        $lonDiff = deg2rad($longitude2 - $longitude1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) *
            sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
