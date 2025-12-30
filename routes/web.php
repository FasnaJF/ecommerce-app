<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

Route::middleware(['auth'])->group(function () {

    Route::get('/', [ProductController::class, 'index']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'history']);

    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/item/{item}', [CartController::class, 'update']);
    Route::delete('/cart/item/{item}', [CartController::class, 'remove']);

    Route::post('/checkout', [OrderController::class, 'checkout']);
});
