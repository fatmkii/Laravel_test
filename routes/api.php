<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ForumController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
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

// Route::get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/get_user', [UserController::class, 'get_user']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('forums', ForumController::class);

//thread系列
Route::post('/threads/create', [ThreadController::class, 'create'])->name('thread.create');
Route::apiResource('threads', ThreadController::class);

//post系列
Route::post('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::apiResource('posts', PostController::class);

//user系列
Route::get('/user/{binggan}', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::post('/user/register', [UserController::class, 'create']);


//测试用
Route::middleware('auth:sanctum')->post('/get_user', [AuthController::class, 'get_user']);
