<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            RolesSeeder::class,
            ProductsTableSeeder::class,
            StoresTableSeeder::class,
            CategoriesTableSeeder::class,
            ProductCategoryTableSeeder::class,
            AdsTableSeeder::class,

            //ProductsImagesTableSeeder::class,
        ]);
    }
}
