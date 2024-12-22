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
            ['name' => 'Electronics','color' => 'red', 'parent_id' => null],
            ['name' => 'Fashion', 'color' => 'blue', 'parent_id' => null],
            ['name' => 'Men', 'color' => 'blue', 'parent_id' => 2],
            ['name' => 'Women', 'color' => 'green', 'parent_id' => 2],
        ]);
    }
}
