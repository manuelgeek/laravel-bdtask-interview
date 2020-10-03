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

Route::fallback(function(){
    return response()->json(['message' => 'Not Found!'], 404);
});

Route::group(['namespace' => 'API', 'prefix' => 'v1'], function () {
    Route::post('/social_login/{provider}', [\App\Http\Controllers\API\AuthController::class, 'socialLogin']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/sale-made', [\App\Http\Controllers\API\SalesController::class, 'sale']);
        Route::get('/sales-made', [\App\Http\Controllers\API\SalesController::class, 'sales']);
        Route::post('/logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);
    });
});
