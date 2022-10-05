<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        $password = Hash::make('1234567890');
        $users =  [[
            "name" => "admin",
            "username" => "admin",
            "password" => $password,
            "email" => 'admin@test.com',
            "role" => "ADMIN"
        ], [
            "name" => "productOwner",
            "username" => "productOwner",
            "password" => $password,
            "email" => 'productOwner@test.com',
            "role" => "PRODUCT_OWNER"
        ], [
            "name" => "user1",
            "username" => "user1",
            "password" => $password,
            "email" => 'user1@test.com',
            "role" => "USER"
        ], [
            "name" => "user2",
            "username" => "user2",
            "password" => $password,
            "email" => 'user2@test.com',
            "role" => "USER"
        ]];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
