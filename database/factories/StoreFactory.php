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
            'image' => $this->faker->imageUrl(640, 480, 'business', true),
        ];
    }
}
