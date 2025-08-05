<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create(
[
                //'id' => 2,
                'name' => 'test_1',
                'email' => 'email_2@test.com',
                'password' => Hash::make('123'), // <-- Here
                'email_verified_at'=> null,
                'remember_token'=> null
            ]
        );

        //
        User::create(
[
                //'id' => 3,
                'name' => 'test_3',
                'email' => 'email_3@test.com',
                'password' => Hash::make('123'), // <-- Here
                'email_verified_at' => null,
                'remember_token' => null
            ]
        );
    }
}
