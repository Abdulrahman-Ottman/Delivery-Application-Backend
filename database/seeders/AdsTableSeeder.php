<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ads')->insert([
            ['image'=>'C:\Projects\Laravel Projects\laptop.jfif' , 'store_id'=>1],
            ['image'=>'C:\Projects\Laravel Projects\laptop.jfif' , 'store_id'=>2],
            ['image'=>'C:\Projects\Laravel Projects\laptop.jfif' , 'store_id'=>3],
            ['image'=>'C:\Projects\Laravel Projects\laptop.jfif' , 'store_id'=>4]
        ]);
    }
}
