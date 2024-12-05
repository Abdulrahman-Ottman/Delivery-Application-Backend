<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class ProcessLocation implements ShouldQueue
{
    use Queueable;

    protected $userId;
    protected $location;
    /**
     * Create a new job instance.
     */
    public function __construct( $userId,  $location)
    {
        $this->userId = $userId;
        $this->location = $location;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $country = $this->location['country'];
        $city = $this->location['city'];

        $url = 'https://nominatim.openstreetmap.org/search';
        $response = Http::withHeaders([
            'User-Agent' => 'Delivery-Backend-Application/1.0 (aboodoth75@gmail.com)',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $city .','. $country,
            'format' => 'json',
            'addressdetails' => 1,
        ]);
        $latitude = 0;
        $longitude = 0;
        if ($response->successful()) {
            $response = $response->json();

            if (count($response) > 0 && isset($response[0]['lat']) && isset($response[0]['lon'])) {
                $latitude = $response[0]['lat'];
                $longitude = $response[0]['lon'];
            }

        }
        $user = User::find($this->userId);
        $user->update([
            'location' => json_encode([
                'country' => $country,
                'city' => $city,
                'address' => $this->location['address'],
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]),
        ]);
    }
}
