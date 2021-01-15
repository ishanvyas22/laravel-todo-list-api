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

    /** @test */
    public function a_user_can_view_list_of_pending_tasks_along_with_its_subtasks()
    {
        $this->withoutExceptionHandling();

        $task = Task::factory()->create([
            'title' => 'Go to the store',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
        ]);
        Task::factory()->create([
            'parent_id' => $task->id,
            'title' => 'Buy milk',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
        ]);
        Task::factory()->create([
            'parent_id' => $task->id,
            'title' => 'Buy potato chips',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
        ]);

        $response = $this->getJson('/api/tasks');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'current_page',
                'data' => [
                    [
                        'title',
                        'due_date',
                        'status',
                        'created_at',
                        'updated_at',
                        'subtasks' => [
                            [
                                'title',
                                'due_date',
                                'status',
                                'created_at',
                                'updated_at',
                            ],
                        ],
                    ],
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ])
            ->assertJsonPath('total', 1);
    }
}
