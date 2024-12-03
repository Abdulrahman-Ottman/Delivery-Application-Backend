<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stores')->insert([
            [
                'name' => 'Apple Store',
                'location' => json_encode([
                    'country' => 'Syria',
                    'city' => 'Damascus'
                ]),
            ],

        ]);
    }
}
