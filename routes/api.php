<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/post/{id}','App\Http\Controllers\PostController@show');
Route::post('/post/create','App\Http\Controllers\PostController@store');
Route::post('/post/update','App\Http\Controllers\PostController@update');
Route::delete('/post/{id}','App\Http\Controllers\PostController@destroy');

