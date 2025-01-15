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
            ['image'=>'storage/images/ads/y10fXAzUOeGuX0lqbyGG6fH6NWI7gOZ0hDO9DAIY.jpg' , 'store_id'=>6],
            ['image'=>'storage/images/ads/NBa1FEaRQ3b72326o07z06dDRo7Z7bx8PiQrDEht.jpg' , 'store_id'=>1],
            ['image'=>'storage/images/ads/4wc3lXcBwIxdoPwdoMfxLsRwnx0g7m2zXdZYwEoS.jpg' , 'store_id'=>4],
        ]);
    }
}
