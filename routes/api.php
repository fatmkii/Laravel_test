<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ForumController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EmojiController;
use App\Http\Controllers\API\ThreadController;
use App\Http\Controllers\API\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



//Auth系列
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//Forum系列
Route::apiResource('forums', ForumController::class);

//thread系列
Route::post('/threads/create', [ThreadController::class, 'create'])->name('thread.create');
Route::apiResource('threads', ThreadController::class);

//Post系列
Route::post('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::apiResource('posts', PostController::class);

//User系列
Route::post('/user/show', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::post('/user/register', [UserController::class, 'create']);

//Emoji系列
Route::get('/emoji', [EmojiController::class, 'index']);


//测试用
Route::middleware('auth:sanctum')->post('/get_user', [AuthController::class, 'get_user']);
