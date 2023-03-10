<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CommentController;
use App\Http\Middleware\RoleChecker;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [App\Http\Controllers\API\RegisterController::class, 'register']);
Route::post('login', [App\Http\Controllers\API\RegisterController::class, 'login']);
Route::get('get-user', [App\Http\Controllers\API\RegisterController::class, 'userInfo']);

Route::post('comment', [App\Http\Controllers\API\CommentController::class, 'store']);

Route::middleware(['auth:api', RoleChecker::class])->group( function () {
    Route::resource('news', App\Http\Controllers\API\NewsController::class);
});
