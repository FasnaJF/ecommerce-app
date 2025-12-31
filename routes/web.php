<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    // If user is authenticated, show products, otherwise show welcome page
    if (Auth::check()) {
        return app(ProductController::class)->index();
    }
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'history']);

    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/item/{item}', [CartController::class, 'update']);
    Route::delete('/cart/item/{item}', [CartController::class, 'remove']);

    Route::post('/checkout', [OrderController::class, 'checkout']);
});

require __DIR__.'/settings.php';