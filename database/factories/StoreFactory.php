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
            'discount' => 10.5,
            'image' => 'storage/images/stores/store.png',
            'user_id' => 1,
        ];
    }
}
