<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone' => '+963999999998',
                'password' => Hash::make('password'),
                'location' => json_encode([
                    'city' => 'Damascus',
                    'country' => 'Syria',
                    'address' => 'Example Street 123'
                ]),
                'phone_verified_at' => Carbon::now()
            ],
            [
                'first_name' => 'Alaa',
                'last_name' => 'Test',
                'phone' => '+963999999878',
                'password' => Hash::make('password'),
                'location' => json_encode([
                    'city' => 'New York',
                    'country' => 'USA',
                    'address' => 'Example Street 123'
                ]),
                'phone_verified_at' => Carbon::now()
            ]
        ]);
    }
}
