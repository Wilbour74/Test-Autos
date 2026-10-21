<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/user/create', [UserController::class, 'add']);
Route::post('/user/{user}/task/create', [TaskController::class, 'add']);
Route::get('/user/{user}/tasks', [UserController::class, 'getTasks']);