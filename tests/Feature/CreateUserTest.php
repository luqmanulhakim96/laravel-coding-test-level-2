<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $token = $this->getToken('admin');

        $password = Hash::make('1234567890');

        $users['data'] = [
            [
                "name" => "user3",
                "username" => "user3",
                "password" => $password,
                "email" => 'user3@test.com',
                "role" => "USER"
            ], [
                "name" => "user4",
                "username" => "user4",
                "password" => $password,
                "email" => 'user4@test.com',
                "role" => "USER"
            ], [
                "name" => "productOwner2",
                "username" => "productOwner2",
                "password" => $password,
                "email" => 'productOwner2@test.com',
                "role" => "PRODUCT_OWNER"
            ],
        ];

        // # Post Data
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v1/users', $users);


        $response->assertStatus(200);
    }
}
