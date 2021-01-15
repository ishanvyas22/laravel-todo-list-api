<?php

namespace Tests\Feature;

use App\Models\Task;
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

    /** @test */
    public function a_user_can_create_a_subtask()
    {
        $this->withoutExceptionHandling();

        $task = Task::factory()->create();

        $response = $this->postJson('/api/tasks', [
            'parent_id' => $task->id,
            'title' => 'Buy potato chips',
            'due_date' => '2021-01-20',
        ]);

        $response->assertStatus(201)->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseHas('tasks', [
            'parent_id' => $task->id,
            'title' => 'Buy potato chips',
            'due_date' => '2021-01-20',
            'status' => false,
            'deleted_at' => null,
        ]);
    }
}
