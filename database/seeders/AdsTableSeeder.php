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
            ['image'=>'storage/images/ads/ads.png' , 'store_id'=>1],
            ['image'=>'storage/images/ads/ads.png' , 'store_id'=>2],
            ['image'=>'storage/images/ads/ads.png' , 'store_id'=>3],
            ['image'=>'storage/images/ads/ads.png' , 'store_id'=>4],
        ]);
    }
}
