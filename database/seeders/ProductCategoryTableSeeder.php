<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category_product')->insert([
            ['product_id' => 1, 'category_id' => 1], // laptop in Electronics
            ['product_id' => 2, 'category_id' => 2], // t-shirt in fashion
            ['product_id' => 2, 'category_id' => 3], // t-shirt in men (subcategory)
        ]);
    }
}
