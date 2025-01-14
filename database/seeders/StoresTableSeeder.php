<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StoresTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Store::factory()->count(20)->create();

        $faker = Faker::create(); // Create a Faker instance

        $stores = [
            'Chic & Tech',
            'Read & Feast',
            'Gadgets & Garments',
            'The Trendy Shelf',
            'Fashion Bytes',
            'Culinary Reads',
            'Electronic Style',
            'Books & Bites',
            'Urban Outfitters & Electronics',
            'The Stylish Market',
        ];
        $i = 4;
        $numbers = [10, 40, 0];
        foreach ($stores as $storeName) {
            $randomNumber = $numbers[array_rand($numbers)];
            Store::create([
                'name' => $storeName,
                'location' => json_encode([
                    'city' => $faker->city,
                    'country' => $faker->country,
                    'latitude' => $faker->latitude,
                    'longitude' => $faker->longitude,
                ]),
                'discount' => $randomNumber,
                'image' => 'storage/images/stores/store.png',
                'user_id' => $i++,
            ]);
        }
    }
}
