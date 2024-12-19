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
            ['name' => 'Electronics', 'parent_id' => null],
            ['name' => 'Fashion', 'parent_id' => null],
            ['name' => 'Men', 'parent_id' => 2],
            ['name' => 'Women', 'parent_id' => 2],
        ]);
    }
}
