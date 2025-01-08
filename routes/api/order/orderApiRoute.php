<?php

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Favourite\FavouriteController;
use App\Http\Controllers\Api\Order\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('orders')->name('orders.')->group(function () {
        // Customer routes
        Route::middleware('abilities:' . Constants::customer_guard)->group(function () {
            Route::post('/', [OrderController::class, 'createOrder'])->name('create');
            Route::post('/items/{orderItem?}/payment', [OrderController::class, 'processPayment'])->name('items.payment.process');
            Route::post('/items/{orderItem?}/cancel', [OrderController::class, 'cancelOrderItem'])->name('items.cancel');
        });

        // Store owner routes
        Route::middleware('abilities:' . Constants::store_owner_guard)->group(function () {
            Route::patch('/items/{orderItem?}/status', [OrderController::class, 'updateOrderItemStatus'])->name('items.status.update');
        });
    });
});
// Route::middleware(['auth:sanctum'])->group(function () {
//     // Customer routes
//     Route::middleware('abilities:' . Constants::customer_guard)->group(function () {
//         Route::post('/', [OrderController::class, 'createOrder'])
//             ->name('create');

//         Route::post('/items/{orderItemId}/payment', [OrderController::class, 'processPayment'])
//             ->name('payment.process');

//         Route::post('/items/{orderItemId}/cancel', [OrderController::class, 'cancelOrderItem'])
//             ->name('items.cancel');
//     });

//     // Store owner routes
//     Route::middleware('abilities:' . Constants::store_owner_guard)->group(function () {
//         Route::patch('/items/{orderItemId}/status', [OrderController::class, 'updateOrderItemStatus'])
//             ->name('items.status.update');
//     });
// });
