<?php

namespace Tests\Feature;

use App\Models\Task;
use Carbon\Carbon;
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
                        'subtasks' => [],
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

    /** @test */
    public function a_user_can_mark_a_task_complete()
    {
        $this->withoutExceptionHandling();

        $task = Task::factory()->create([
            'title' => 'Go to the store',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => false,
        ]);

        $response = $this->putJson('/api/tasks/complete', ['task_id' => $task->id]);

        $response->assertStatus(200)->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Go to the store',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);
    }

    /** @test */
    public function a_user_can_delete_a_task()
    {
        $this->withoutExceptionHandling();

        $task = Task::factory()->create([
            'title' => 'Go to the store',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Go to the store',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
            'deleted_at' => now(),
        ]);
    }

    /** @test */
    public function a_user_can_search_tasks_by_title()
    {
        $this->withoutExceptionHandling();

        Task::factory()->create([
            'title' => 'Go to the store',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);
        Task::factory()->create([
            'title' => 'Clean up room',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);
        Task::factory()->create([
            'title' => 'Clean up garage',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);

        $response = $this->getJson('/api/tasks/filter?title=Clean');

        $response->assertStatus(200)->assertJsonPath('total', 2);
    }

    /** @test */
    public function a_user_can_filter_tasks_which_due_today()
    {
        $this->withoutExceptionHandling();

        Task::factory()->create([
            'title' => 'Go to the store',
            'due_date' => now()->format('Y-m-d'),
            'status' => true,
        ]);
        Task::factory()->create([
            'title' => 'Clean up room',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);
        Task::factory()->create([
            'title' => 'Clean up garage',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);

        $response = $this->getJson('/api/tasks/filter?due_date=today');

        $response->assertStatus(200)->assertJsonPath('total', 1);
    }

    /** @test */
    public function a_user_can_filter_tasks_which_is_due_this_week()
    {
        $this->withoutExceptionHandling();

        Task::factory()->create([
            'title' => 'Go to the store',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);
        Task::factory()->create([
            'title' => 'Clean up room',
            'due_date' => now()->add(2, 'day')->format('Y-m-d'),
            'status' => true,
        ]);
        Task::factory()->create([
            'title' => 'Clean up garage',
            'due_date' => now()->add(10, 'day')->format('Y-m-d'),
            'status' => true,
        ]);

        $response = $this->getJson('/api/tasks/filter?due_date=this_week');

        $response->assertStatus(200)->assertJsonPath('total', 2);
    }

    /** @test */
    public function a_user_can_filter_tasks_which_is_due_next_week()
    {
        $this->withoutExceptionHandling();

        Task::factory()->create([
            'title' => 'Go to the store',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);
        Task::factory()->create([
            'title' => 'Clean up room',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);
        Task::factory()->create([
            'title' => 'Clean up garage',
            'due_date' => now()->add(4, 'day')->format('Y-m-d'),
            'status' => true,
        ]);

        $response = $this->getJson('/api/tasks/filter?due_date=next_week');

        $response->assertStatus(200)->assertJsonPath('total', 1);
    }

    /** @test */
    public function a_user_can_filter_tasks_which_is_overdue()
    {
        $this->withoutExceptionHandling();

        Task::factory()->create([
            'title' => 'Go to the store',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);
        Task::factory()->create([
            'title' => 'Clean up room',
            'due_date' => now()->add(1, 'day')->format('Y-m-d'),
            'status' => true,
        ]);
        Task::factory()->create([
            'title' => 'Clean up garage',
            'due_date' => Carbon::yesterday(),
            'status' => true,
        ]);

        $response = $this->getJson('/api/tasks/filter?due_date=overdue');

        $response->assertStatus(200)->assertJsonPath('total', 1);
    }
}
