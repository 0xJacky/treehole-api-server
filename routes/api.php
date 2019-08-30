<?php

use Illuminate\Http\Request;

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

Route::prefix('admin')->group(function () {
   Route::post('/login', 'AuthController@store');
   Route::delete('/logout', 'AuthController@destroy');
});

Route::prefix('posts')->group(function () {
    Route::get('/', 'PostController@get_list');
    Route::post('/', 'PostController@store');
});

Route::prefix('comments')->group(function () {
    Route::get('/', 'CommentController@get');
    Route::post('/', 'CommentController@store');
});
