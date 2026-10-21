<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[DataProvider('validAndInvalidTasks')]
    public function test_create_task(string $title, string $description, int $status): void
    {
        $response = $this->postJson("/api/user/{$this->user->id}/task/create", [
            'name' => $title,
            'content' => $description,
        ]);

        $response->assertStatus($status);
    }

    #[DataProvider('maxTasks')]
    public function test_maximum_tasks(int $existingTasks, int $status, int $expectedTaskCount): void
    {
        for ($taskNumber = 1; $taskNumber <= $existingTasks; $taskNumber++) {
            $this->user->tasks()->create([
                'name' => "Existing task {$taskNumber}",
                'content' => 'Existing task content.',
            ]);
        }

        $response = $this->postJson("/api/user/{$this->user->id}/task/create", [
            'name' => 'New task',
            'content' => 'New task content.',
        ]);

        $response->assertStatus($status);
        $this->assertSame($expectedTaskCount, $this->user->tasks()->count());
    }

    public function test_create_task_without_user(): void
    {
        $invalidUserId = $this->user->id + 1;
        $response = $this->postJson("/api/user/{$invalidUserId}/task/create", [
            'name' => 'Test Task',
            'content' => 'This is a test task.',
        ]);

        $response->assertStatus(404);
    }

    public function test_get_tasks_by_user(): void
    {
        for($i = 1; $i <=3; $i++) {
            $this->user->tasks()->create([
                'name' => "Task {$i}",
                'content' => "Content for Task {$i}",
            ]);
        }

        $response = $this->getJson("/api/user/{$this->user->id}/tasks");

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public static function validAndInvalidTasks(): array
    {
        return [
            'taskOne' => ['Test Task', 'This is a test task.', 201],
            'taskTwo' => ['Another Task', 'This is another test task.', 201],
            'taskThree' => ['', '', 422],
        ];
    }

    public static function maxTasks(): array
    {
        return [
            'tenth task is allowed' => [9, 201, 10],
            'eleventh task is rejected' => [10, 400, 10],
        ];
    }
}
