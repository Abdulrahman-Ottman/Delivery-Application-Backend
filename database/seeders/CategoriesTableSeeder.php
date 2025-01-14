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
            ['name' => 'Electronics','color' => '#fff234fa','image'=>'storage/images/categories/category.svg', 'parent_id' => null],
            ['name' => 'Fashion', 'color' => '#fff234fa','image'=>'storage/images/categories/category.svg', 'parent_id' => null],
            ['name' => 'Men', 'color' => '#fff234fa','image'=>'storage/images/categories/category.svg', 'parent_id' => 2],
            ['name' => 'Women', 'color' => '#fff234fa','image'=>'storage/images/categories/category.svg', 'parent_id' => 2],
        ]);
    }
}
