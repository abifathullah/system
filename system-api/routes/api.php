<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers')->group(function () {
    Route::post('/login', 'AuthController@login');
    Route::get('/versions/base', 'VersionController@getVersions');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'AuthController@logout');
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::controller('ProductCategoryController')->group(function () {
            Route::apiResource('/product-categories', 'ProductCategoryController');
        });

        Route::controller('ProductController')->group(function () {
            Route::apiResource('/products', 'ProductController');
            Route::get('/products-filter', 'filter');
        });
    });
});
