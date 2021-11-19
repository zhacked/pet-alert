<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'type' => 'admin',
                'bio' =>'administrator',
                'photo' => null,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'tech',
                'email' => 'technical@gmail.com',
                'type' => 'admin',
                'bio' =>'technical department',
                'photo' => null,
                'password' => Hash::make('password'),
            ]
        ]);
    }
}
