<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function add(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $data['user_id'] = $user->id;

        $task = (new TaskService())->createTask($data);

        if (!$task) {
            return response()->json("Problème lors de la création", 400);
        }

        return response()->json($task, 201);
    }

    public function getTasks(User $user) 
    {
        return response()->json($user->tasks()->get());
    }
}
