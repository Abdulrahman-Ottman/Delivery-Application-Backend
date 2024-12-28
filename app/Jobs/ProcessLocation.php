<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class ProcessLocation implements ShouldQueue
{
    use Queueable;

    protected $object;
    protected $location;
    /**
     * Create a new job instance.
     */
    public function __construct( $object,  $location)
    {
        $this->object = $object;
        $this->location = $location;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $country = $this->location['country'];
            $city = $this->location['city'];

            $response = Http::withHeaders([
                'User-Agent' => 'Delivery-Backend-Application/1.0 (aboodoth75@gmail.com)',
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $city . ',' . $country,
                'format' => 'json',
                'addressdetails' => 1,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch coordinates from Nominatim API');
            }

            $responseData = $response->json();
            if (empty($responseData) || !isset($responseData[0]['lat'], $responseData[0]['lon'])) {
                throw new \Exception('Invalid response structure from Nominatim API');
            }

            $latitude = $responseData[0]['lat'];
            $longitude = $responseData[0]['lon'];


            if (!$this->object) {
                    throw new \Exception("Object not found");
            }
            $address = null;
            if(array_key_exists('address', $this->location))
                $address = $this->location['address'];
            $this->object->update([
                'location' => json_encode([
                    'country' => $country,
                    'city' => $city,
                    'address' => $address,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]),
            ]);
        } catch (\Exception $e) {
            \Log::error("Job failed: {$e->getMessage()}");
            throw $e;
        }
    }
}
