<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Electronics',
                'color' => '#fff234fa',
                'image' => 'storage/images/categories/electronics.svg',
                'parent_id' => null
            ],
            [
                'name' => 'Men',
                'color' => '#1f3a3d',
                'image' => 'storage/images/categories/fashion_men.svg',
                'parent_id' => 6
            ],
            [
                'name' => 'Women',
                'color' => '#ff6f61',
                'image' => 'storage/images/categories/fashion_women.svg',
                'parent_id' => 6
            ],
            [
                'name' => 'Food',
                'color' => '#ffcc00',
                'image' => 'storage/images/categories/food.svg',
                'parent_id' => null
            ],
            [
                'name' => 'Books',
                'color' => '#4caf50',
                'image' => 'storage/images/categories/books.svg',
                'parent_id' => null
            ],
            [
                'name' => 'Fashion',
                'color' => '#fff234fa',
                'image' => 'storage/images/categories/fashion.svg',
                'parent_id' => null
            ],
        ]);

    }
}
