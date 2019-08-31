<?php

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

Route::prefix('admin')->group(function () {
    Route::post('/login', 'AuthController@store');
    Route::delete('/logout', 'AuthController@destroy');
});

Route::prefix('posts')->group(function () {
    Route::get('/', 'PostController@get_list');
    Route::post('/', 'PostController@store');
    Route::delete('/', 'PostController@destroy');
});

Route::prefix('categories')->group(function () {
    Route::get('/', 'CategoryController@get_list');
    Route::post('/', 'CategoryController@store');
    Route::delete('/', 'CategoryController@destroy');
});

Route::prefix('comments')->group(function () {
    Route::get('/', 'CommentController@get');
    Route::post('/', 'CommentController@store');
});

Route::prefix('frontend')->group(function () {
    Route::get('/home', 'Frontendcontroller@home');
});
