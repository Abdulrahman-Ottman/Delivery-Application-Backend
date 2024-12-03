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
            ['name' => 'Electronics', 'parent_id' => null], // Main category
            ['name' => 'Fashion', 'parent_id' => null],     // Main category
            ['name' => 'Men', 'parent_id' => 2],           // Subcategory of Fashion
            ['name' => 'Women', 'parent_id' => 2],         // Subcategory of Fashion
        ]);
    }
}
