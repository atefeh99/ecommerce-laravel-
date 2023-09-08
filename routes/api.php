<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [ 'as' => 'login',AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group([
    'prefix' => 'orders'
], function ($router) {
    Route::get('', [OrderController::class, 'index']);
    Route::post('', [OrderController::class,'store']);
    Route::get('/{id}', [OrderController::class,'show']);
    Route::patch('/{id}', [OrderController::class,'update']);
    Route::delete('/{id}', [OrderController::class,'destroy']);
});

Route::apiResource('products', ProductController::class,['except' => ['update']]);
Route::patch('products/{id}', '\App\Http\Controllers\ProductController@UpdateItem');

Route::apiResource('orders', OrderController::class,['except' => ['update']]);
Route::patch('orders/{id}', '\App\Http\Controllers\OrderController@UpdateItem');
