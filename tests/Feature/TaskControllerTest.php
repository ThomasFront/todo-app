<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_retrieve_all_tasks()
    {
        Task::factory()->count(5)->create(['is_completed' => false]);
        Task::factory()->count(3)->create(['is_completed' => true]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200);
        $response->assertJsonCount(8, 'data');
    }

    public function test_can_retrieve_completed_tasks()
    {
        Task::factory()->count(3)->create(['is_completed' => true]);
        Task::factory()->count(2)->create(['is_completed' => false]);

        $response = $this->getJson('/api/tasks?status=completed');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_retrieve_incomplete_tasks()
    {
        Task::factory()->count(4)->create(['is_completed' => false]);
        Task::factory()->count(1)->create(['is_completed' => true]);

        $response = $this->getJson('/api/tasks?status=incomplete');

        $response->assertStatus(200);
        $response->assertJsonCount(4, 'data');
    }

    public function test_can_create_task()
    {
        $data = [
            'title' => 'Test Task',
            'details' => 'Task details.',
            'deadline' => now()->addDays(10)
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_can_retrieve_single_task()
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => $task->title]);
        $response->assertJsonFragment(['details' => $task->details]);
        $response->assertJsonFragment(['isCompleted' => $task->is_completed]);
        $response->assertJsonFragment(['deadline' => $task->deadline->toISOString()]);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->create();
        $data = [
            'title' => 'Updated Title',
            'details' => 'Updated description.',
            'deadline' => "2024-12-10 21:52:11"
        ];

        $response = $this->patchJson("/api/tasks/{$task->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_can_toggle_task_completion()
    {
        $task = Task::factory()->create();
        $data = ['is_completed' => true];

        $response = $this->patchJson("/api/tasks/{$task->id}/complete", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_completed' => true]);
    }

    public function test_can_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}
