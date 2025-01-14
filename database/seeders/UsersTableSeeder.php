<?php

namespace Database\Seeders;

use App\Jobs\ProcessLocation;
use App\Models\User;
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
                'phone_verified_at' => Carbon::now(),
                'role_id' => '1'
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
                'phone_verified_at' => Carbon::now(),
                'role_id' => '2',

            ],
            [
                'first_name' => 'Test',
                'last_name' => 'Test',
                'phone' => '+963999999877',
                'password' => Hash::make('password'),
                'location' => json_encode([
                    'city' => 'New York',
                    'country' => 'USA',
                    'address' => 'Example Street 123'
                ]),
                'phone_verified_at' => Carbon::now(),
                'role_id' => '3',
            ]
        ]);
        $i=0;
        while($i++<10){
            DB::table('users')->insert([
                [
                    'first_name' => 'Alaa'.$i,
                    'last_name' => 'Test',
                    'phone' => '+96399999986'.$i,
                    'password' => Hash::make('password'),
                    'location' => json_encode([
                        'city' => 'New York',
                        'country' => 'USA',
                        'address' => 'Example Street 123'
                    ]),
                    'phone_verified_at' => Carbon::now(),
                    'role_id' => '2',
                ]
            ]);
        }
        foreach (User::all() as $user) {
            $location = json_decode($user->location, true);
            ProcessLocation::dispatch($user, $location);
        }

    }
}
