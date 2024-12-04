<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word, // Random product name
            'description' => $this->faker->sentence(10), // Random product description
            'price' => $this->faker->randomFloat(2, 1, 100), // Random price between 1 and 100
            'quantity' => $this->faker->numberBetween(1, 100), // Random quantity between 1 and 100
            'details' => json_encode(['color' => $this->faker->colorName, 'size' => $this->faker->word]), // Random details
            'store_id' => \App\Models\Store::factory(), // Automatically create a store for this product
        ];
    }
}
