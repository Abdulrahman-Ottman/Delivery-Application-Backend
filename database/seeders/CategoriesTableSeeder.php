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
                'color' => '#fcba03',
                'image' => 'storage/images/categories/electronics.svg',
                'parent_id' => null
            ],
            [
                'name' => 'Men',
                'color' => '#1f3a3d',
                'image' => 'storage/images/categories/men.jpg',
                'parent_id' => 6
            ],
            [
                'name' => 'Women',
                'color' => '#ff6f61',
                'image' => 'storage/images/categories/women.jpg',
                'parent_id' => 6
            ],
            [
                'name' => 'Food',
                'color' => '#e6ac55',
                'image' => 'storage/images/categories/food.svg',
                'parent_id' => null
            ],
            [
                'name' => 'Books',
                'color' => '#03cefc',
                'image' => 'storage/images/categories/books.svg',
                'parent_id' => null
            ],
            [
                'name' => 'Fashion',
                'color' => '#fff234fa',
                'image' => 'storage/images/categories/fashion.svg',
                'parent_id' => null
            ],
            [
                'name' => 'PC Accessories',
                'color' => '#fff234fa',
                'image' => 'storage/images/categories/pc-accessories.jpg',
                'parent_id' => 1
            ],
            [
                'name' => 'Keyboards',
                'color' => '#fff234fa',
                'image' => 'storage/images/categories/keyboards.png',
                'parent_id' => 7
            ],
            [
                'name' => 'shoes',
                'color' => '#1f3a3d',
                'image' => 'storage/images/categories/shoes.jpg',
                'parent_id' => 2
            ],
            [
                'name' => 'pants',
                'color' => '#ff6f61',
                'image' => 'storage/images/categories/pants.jpg',
                'parent_id' => 3
            ],
            [
                'name' => 'Chines',
                'color' => '#ffcc00',
                'image' => 'storage/images/categories/chines.jpg',
                'parent_id' => 4
            ],
            [
                'name' => 'Tea',
                'color' => '#ffcc00',
                'image' => 'storage/images/categories/chinese-tea-culture-top.jpg',
                'parent_id' => 11
            ],
            [
                'name' => 'Religious',
                'color' => '#4caf50',
                'image' => 'storage/images/categories/religious.jpeg',
                'parent_id' => 5
            ],
            [
                'name' => 'Christian',
                'color' => '#4caf50',
                'image' => 'storage/images/categories/christian.jpg',
                'parent_id' => 13
            ],
        ]);
    }
}
