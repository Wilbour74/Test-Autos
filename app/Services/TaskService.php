<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public function createTask(array $data): Task|null
    {
        $tasks = Task::where('user_id', $data['user_id'])->count();
        if ($tasks >= 10) {
            return null;
        }

        $task = new Task();
        $task->name = $data['name'];
        $task->content = $data['content'];
        $task->user_id = $data['user_id'];
        $task->save();
        return $task;
    }

    public function getTasksByUserId(int $userId)
    {
        return Task::where('user_id', $userId)->get();
    }
}
