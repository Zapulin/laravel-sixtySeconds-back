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

Route::get('/posts', 'App\Http\Controllers\PostController@Index');
Route::get('/post/{id}','App\Http\Controllers\PostController@show');//->middleware('auth:sanctum');
Route::post('/post/create','App\Http\Controllers\PostController@store');//->middleware('auth:sanctum');
Route::post('/post/update','App\Http\Controllers\PostController@update');//->middleware('auth:sanctum');
Route::delete('/post/{id}','App\Http\Controllers\PostController@destroy');//->middleware('auth:sanctum');

Route::post('/auth/register', [UsuarioController::class, 'register']);
Route::post('/auth/login', [UsuarioController::class, 'login']);
Route::post('/auth/logout', [UsuarioController::class, 'logout']);
