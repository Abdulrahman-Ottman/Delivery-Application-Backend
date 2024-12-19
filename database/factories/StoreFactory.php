<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition()
    {

        return [
            'name' => $this->faker->company,
            'location' => json_encode([
                'city' => $this->faker->city,
                'country' => $this->faker->country,
                'latitude' => $this->faker->latitude,
                'longitude' => $this->faker->longitude,
            ]),
            'image' => 'http://192.168.1.5:8000/storage/images/stores/store.png',
        ];
    }
}
