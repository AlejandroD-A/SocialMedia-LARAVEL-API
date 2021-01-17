<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\TagController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('post', PostController::class)->only([
    'index', 'show'
]);

Route::get('post/t/{tag}', [PostController::class, 'tag']);

Route::get('tag', [TagController::class, 'index']);
Route::get('tag/thrends', [TagController::class, 'thrends']);
