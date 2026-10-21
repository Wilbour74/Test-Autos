<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function add(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'birthdate' => 'nullable|date',
        ]);

        $user = (new UserService())->createUser($data);

        return response()->json($user, 201);
    }

    public function getTasks(User $user) 
    {
        return response()->json($user->tasks()->get());
    } 
}
