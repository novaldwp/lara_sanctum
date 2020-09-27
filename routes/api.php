<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function() {
    Route::post('/login', 'API\v1\AuthController@login')->name('login');

    Route::group([
        'middleware' => 'auth:sanctum', 'json.response'
    ], function() {
        Route::post('/register', 'API\v1\AuthController@register')->name('register');
        Route::get('/logout', 'API\v1\AuthController@logout')->name('logout');
        Route::get('/users', 'API\v1\UserController@index')->name('user');
    });

    Route::resource('products', 'API\v1\ProductController');
});
