<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait CalculatesDistance
{
    public function CalculateDistance(string $location1 , string $location2)
    {
        $url = 'https://nominatim.openstreetmap.org/search';
        $response1 = Http::withHeaders([
            'User-Agent' => 'Delivery-Backend-Application/1.0 (aboodoth75@gmail.com)',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $location1,
            'format' => 'json',
            'addressdetails' => 1,
        ]);

        $response2 = Http::withHeaders([
            'User-Agent' => 'Delivery-Backend-Application/1.0 (aboodoth75@gmail.com)',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $location2,
            'format' => 'json',
            'addressdetails' => 1,
        ]);
//        dd($response1->json(), $response2->json());
        $response1 = $response1->json();
        $response2 = $response2->json();

        $latitude1 = 0;
        $longitude1 = 0;
        $latitude2 = 0;
        $longitude2 = 0;

        if (count($response1) > 0 && isset($response1[0]['lat']) && isset($response1[0]['lon'])) {
            $latitude1 = $response1[0]['lat'];
            $longitude1 = $response1[0]['lon'];
        } else {
            return null;
        }

        // check if the city exists for city2
        if (count($response2) > 0 && isset($response2[0]['lat']) && isset($response2[0]['lon'])) {
            $latitude2 = $response2[0]['lat'];
            $longitude2 = $response2[0]['lon'];
        } else {
            return null;
        }

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
