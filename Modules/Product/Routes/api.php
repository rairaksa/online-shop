<?php

use Illuminate\Http\Request;
use Modules\Product\Http\Controllers\ProductController;

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

Route::middleware('api')->prefix('/product')->group(function() {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/detail', [ProductController::class, 'detail']);
    Route::post('/add-to-cart', [ProductController::class, 'add_to_cart']);
});