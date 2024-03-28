<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/tasks/update/status', [TasksController::class, 'updateTaskStatus']);
Route::get('/tasks/file/{id}', [TasksController::class, 'downloadTaskAttachment']);
Route::apiResource('tasks', TasksController::class);
