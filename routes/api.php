<?php

use App\Http\Controllers\api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\TagController;
use App\Http\Controllers\api\CommentaryController;

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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::group(['name' => 'allUsers'], function () {

    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::get('post/{post}/comment', [PostController::class, 'getComments']);

    Route::get('post', [PostController::class, 'index']);
    Route::get('post/{post}', [PostController::class, 'show']);
    Route::get('post/t/{tag}', [PostController::class, 'tag']);
    Route::get('tag', [TagController::class, 'index']);
    Route::get('tag/thrends', [TagController::class, 'thrends']);
});

Route::group(['name' => 'LoggedUsers', 'middleware' => ['auth:api']], function () {
    Route::post('post', [PostController::class, 'store']);
    Route::post('post/{post}/comment', [CommentaryController::class, 'store']);
});

Route::group(['name' => 'OnlyAdmin', 'middleware' => ['auth:api', 'checkAdmin']], function () {
    Route::delete('post/{post}', [PostController::class, 'destroy']);
    Route::post('post/{post}/restore', [PostController::class, 'restore']);
});
