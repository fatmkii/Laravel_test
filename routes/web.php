<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ForumController;
use PHPUnit\Framework\Test;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/testroute', function () {
    return 'OK';
});

Route::get('/test', [TestController::class, 'test']);
Route::get('/test/login', [TestController::class, 'login'])->name('login');
Route::get('/test/request', [TestController::class, 'request']);
Route::get('/test/reply_queue', [TestController::class, 'reply_queue']);
Route::get('/test/index', [TestController::class, 'index']);

Route::prefix('vue')->group(function () {
    Route::get('test', [TestController::class, 'vue_test']);
    Route::get('test/{id}', [TestController::class, 'vue_test']);
});
    
Route::prefix('bs')->group(function () {
    Route::get('test', [TestController::class, 'bs_test']);
    Route::get('test/{id}', [TestController::class, 'bs_test']);
});