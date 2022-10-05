<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChangeTaskStatusTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        # Get Token User
        $token = $this->getToken('user');

        # Get Task Datas
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/tasks');

        $tasks = json_decode($response->content());

        foreach ($tasks->data as $key => $task) {
            $post = [
                "title" => $task->title,
                "description" => $task->description,
                "status" => "IN_PROGRESS"
            ];
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->patch('/api/v1/tasks/' . $task->id, $post);

        }
        $response->assertStatus(200);
    }
}
