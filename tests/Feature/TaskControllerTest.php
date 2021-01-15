<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_task()
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/tasks', [
            'title' => 'Go to the store',
            'due_date' => '2021-01-25',
        ]);

        $response->assertStatus(201)->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseHas('tasks', [
            'parent_id' => null,
            'title' => 'Go to the store',
            'due_date' => '2021-01-25',
            'status' => false,
            'deleted_at' => null,
        ]);
    }
}
