<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User\User;
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
        User::query()->truncate();

        User::query()->insert([

            [
                'id' => 1,
                'username' => 'user1',
                'password' => Hash::make('12345678'),
                'email'    => 'user1@test.com',
                'first_name' => 'user1',
                'last_name' => 'test1',

            ],
            [
                'id' => 2,
                'username' => 'user2',
                'password' => Hash::make('12345678'),
                'email'    => 'user2@test.com',
                'first_name' => 'user2',
                'last_name' => 'test2',
            ],
            [
                'id' => 3,
                'username' => 'user3',
                'password' => Hash::make('12345678'),
                'email'    => 'user3@test.com',
                'first_name' => 'user3',
                'last_name' => 'test3',
            ],

            [
                'id' => 4,
                'username' => 'user4',
                'password' => Hash::make('12345678'),
                'email'    => 'user4@test.com',
                'first_name' => 'user4',
                'last_name' => 'test4',
            ],

        ]);


    }
}
