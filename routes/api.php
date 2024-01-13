<?php

use App\Http\Controllers\Controller;
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

Route::post('/login', [Controller::class, 'login']);
Route::post('/register', [Controller::class, 'register']);
Route::post('/create', [Controller::class, 'createTodo']);
Route::get('/read', [Controller::class, 'readTodo']);
Route::put('/update/{id}', [Controller::class, 'updateTodo']);
Route::delete('/delete/{id}', [Controller::class, 'deleteTodo']);
