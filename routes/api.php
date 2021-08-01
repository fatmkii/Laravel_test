<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ForumController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ThreadController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\AdminController;

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
Route::post('/posts/create_roll', [PostController::class, 'create_roll']);
Route::apiResource('posts', PostController::class);

//User系列
Route::post('/user/show', [UserController::class, 'show'])->middleware('auth:sanctum'); //获得用户信息
Route::post('/user/register', [UserController::class, 'create']);   //新建饼干
Route::post('/user/reward', [UserController::class, 'reward']);     //打赏
Route::get('/user/check_reg_record', [UserController::class, 'check_reg_record']); //返回注册记录TTL
Route::post('/user/pingbici_set', [UserController::class, 'pingbici_set']);     //设定屏蔽词

//Admin系列
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/admin/thread_delete', [AdminController::class, 'thread_delete'])->middleware('auth:sanctum'); //删主题
Route::post('/admin/post_delete', [AdminController::class, 'post_delete'])->middleware('auth:sanctum'); //删帖
Route::post('/admin/post_delete_all', [AdminController::class, 'post_delete_all'])->middleware('auth:sanctum'); //删主题内该作者全部回帖
Route::post('/admin/user_ban', [AdminController::class, 'user_ban'])->middleware('auth:sanctum'); //碎饼
Route::post('/admin/user_lock', [AdminController::class, 'user_lock'])->middleware('auth:sanctum'); //封id（临时）
Route::post('/admin/thread_set_top', [AdminController::class, 'thread_set_top'])->middleware('auth:sanctum'); //设置置顶
Route::post('/admin/thread_cancel_top', [AdminController::class, 'thread_cancel_top'])->middleware('auth:sanctum'); //取消置顶


//杂项
Route::get('/emoji', [CommonController::class, 'emoji_index']);
Route::get('/subtitles', [CommonController::class, 'subtitles_index']);
Route::get('/random_heads', [CommonController::class, 'random_heads_index']);
