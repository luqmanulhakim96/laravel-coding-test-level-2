<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $adminToken = $this->getToken('admin');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken,
        ])->get('/api/v1/users');

        $getUsers = json_decode($response->content());

        $users = [];
        foreach ($getUsers->data as $key => $value) {
            if ($value->role_id == 1) {
                $users[] = $value->id;
            }
            if (count($users) == 2) {
                break;
            }
        }

        $projectResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken,
        ])->getJson('/api/v1/projects', ["q" => "Test Unit Project"]);

        $getProject = json_decode($projectResponse->content());
        $projectId = $getProject->data[0]->id;

        if ($users) {
            //get user token
            $tokenProduct = $this->getToken('product_owner');

            $projects = [
                [
                    "title" => "First Task",
                    "description" => "This is First Task",
                    "project_id" => $projectId
                ], [
                    "title" => "Second Task",
                    "description" => "This is Second Task",
                    "project_id" => $projectId
                ]
            ];

            foreach ($users as $key => $user) {
                $projects[$key]['user_id'] = $user;
                $response = $this->withHeaders([
                    'Authorization' => 'Bearer ' . $tokenProduct,
                ])->post('/api/v1/tasks', $projects[$key]);
            }

            $response->assertStatus(201);
        }
    }
}
