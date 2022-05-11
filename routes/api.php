<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

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

Route::get('/audio/{shortUrl}', 'App\Http\Controllers\AudioController@getAudio');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register', [UsuarioController::class, 'register']);
Route::post('/auth/login', [UsuarioController::class, 'login'])->name('login');
Route::get('/posts', 'App\Http\Controllers\PostController@Index');

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/profile', [UsuarioController::class, 'myProfile']);
    Route::get('/profile/{id}', [UsuarioController::class, 'profile']);
    Route::post('/profile/edit', [UsuarioController::class, 'editProfile']);
    Route::delete('/profile', [UsuarioController::class, 'destroyProfile']);
    Route::get('/post/{id}', 'App\Http\Controllers\PostController@show');
    Route::post('/post/create', 'App\Http\Controllers\PostController@store');
    Route::post('/post/update', 'App\Http\Controllers\PostController@update');
    Route::delete('/post/{id}', 'App\Http\Controllers\PostController@destroy');
    Route::get('/auth/logout', [UsuarioController::class, 'logout']);
});
