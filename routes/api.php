<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\TagController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\ShortController;
use App\Http\Controllers\api\FollowController;
use App\Http\Controllers\api\FavouriteController;
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

Route::group(['name' => 'LoggedUsers', 'middleware' => ['auth:api']], function () {

    Route::group(['prefix' => 'posts'], function () {
        Route::post('/',                  [PostController::class, 'store']);
        Route::get('/perspective',        [PostController::class, 'perspective']);
        Route::post('/{post}/comments',   [CommentaryController::class, 'store']);
        Route::post('/{post}/favourites', [FavouriteController::class, 'storePost']);
    });

    Route::group(['prefix' => 'shorts'], function () {
        Route::post('/',                   [ShortController::class, 'store']);
        Route::post('/{short}/favourites', [FavouriteController::class, 'storeShort']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/favourites',    [FavouriteController::class, 'index']);
        Route::put('/update',    [UserController::class, 'updateProfile']);
        Route::post('/{user}/follow', [FollowController::class, 'store']);
    });
});

Route::group(['name' => 'allUsers'], function () {

    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login',    [AuthController::class, 'login']);

    Route::group(['prefix' => 'posts'], function () {
        Route::get('/{post}/comments', [PostController::class, 'getComments']);
        Route::get('/',                [PostController::class, 'index']);
        Route::get('/{post}',          [PostController::class, 'show']);
        Route::get('/t/{tag}',         [PostController::class, 'tag']);
    });

    Route::group(['prefix' => 'shorts'], function () {
        Route::get('/{short}/comments', [ShortController::class, 'getComments']);
        Route::get('/',                 [ShortController::class, 'index']);
        Route::get('/{short}',          [ShortController::class, 'show']);
        Route::get('/t/{tag}',          [ShortController::class, 'tag']);
    });

    Route::group(['prefix' => 'tags'], function () {
        Route::get('/',               [TagController::class, 'index']);
        Route::get('/thrends',        [TagController::class, 'thrends']);
        Route::get('/search/{param}', [TagController::class, 'search']);
        Route::get('/getAll/{tag}',   [TagController::class, 'getAll']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/{user}',         [UserController::class, 'show']);
        Route::get('/{user}/following', [FollowController::class, 'getFollowing']);
        Route::get('/{user}/followers', [FollowController::class, 'getFollowers']);
    });
});



Route::group(['name' => 'Admin', 'middleware' => ['auth:api', 'checkAdmin']], function () {
    Route::delete('post/{post}',       [PostController::class, 'destroy']);
    Route::post('post/{post}/restore', [PostController::class, 'restore']);
});
