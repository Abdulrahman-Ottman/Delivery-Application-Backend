<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($j=1; $j<=100; $j++)
            for ($i = 1; $i <= random_int(1, 5); $i++) {
                $path = $i === 1
                    ? 'storage/images/products/main-product.png'
                    : 'storage/images/products/product.png';
                DB::table('product_images')->insert([
                    'path' => $path,
                    'is_main' => $i === 1,
                    'product_id' => $j,
                ]);
            }
    }
}
