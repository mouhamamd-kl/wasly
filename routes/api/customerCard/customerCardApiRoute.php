<?php

use App\Constants\Constants;
use App\Http\Controllers\Api\CustomerCard\CustomerCardController;
use Illuminate\Support\Facades\Route;

Route::prefix('/customer-card')->name('customer-card.')->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:' . Constants::customer_guard])->group(function () {
        // Get all customer cards for the authenticated user
        Route::get('/list', [CustomerCardController::class, 'getUserCards'])->name('list');

        // Get a specific customer card by ID
        Route::get('/{id}', [CustomerCardController::class, 'show'])->name('show');

        // Create a new customer card
        Route::post('/create', [CustomerCardController::class, 'create'])->name('create');

        // Update a customer card by ID
        Route::put('/{id}', [CustomerCardController::class, 'update'])->name('update');

        // Delete a customer card by ID
        Route::delete('/{id}', [CustomerCardController::class, 'destroy'])->name('destroy');
    });
});
