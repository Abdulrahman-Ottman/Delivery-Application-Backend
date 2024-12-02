<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Laptop',
                'description' => 'A high-performance laptop.',
                'price' => 1200.00,
                'quantity' => 11,
                'details'=> json_encode([
                    'Ram' => '8 GB',
                    'Hard Disk' => '1 Tera SSD',
                    'CPU' => 'Intel Core i7-12700K'
                ]),
                'store_id' => 1

            ],[
                'name' => 'T-shirt',
                'description' => 'A cotton t-shirt',
                'price' => 10.00,
                'quantity' => 19,
                'details'=> json_encode([
                    'Size' => 'xl',
                    'Color' => 'red',
                ]),
                'store_id' => 1
            ]
        ]);
    }
}
