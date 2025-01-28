<?php

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Cart\CartController;
use App\Http\Controllers\Api\Favourite\FavouriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Cart\ProductController;

Route::middleware(['auth:sanctum', 'abilities:' . Constants::customer_guard])->group(function () {
    Route::post('/add', [CartController::class, 'addToCart'])->name('add');
    Route::delete('/{product_id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/products', [CartController::class, 'getCartUserProducts'])->name('products');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('clear');
});
