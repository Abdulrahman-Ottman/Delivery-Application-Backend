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
            ['image'=>'C:\Projects\Laravel Projects\laptop.jfif'],
            ['image'=>'C:\Projects\Laravel Projects\laptop.jfif'],
            ['image'=>'C:\Projects\Laravel Projects\laptop.jfif'],
            ['image'=>'C:\Projects\Laravel Projects\laptop.jfif']
        ]);
    }
}
