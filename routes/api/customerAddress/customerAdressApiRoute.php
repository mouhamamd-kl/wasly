<?php

use App\Constants\Constants;
use App\Http\Controllers\Api\CustomerAddress\CustomerAddressController;
use Illuminate\Support\Facades\Route;

Route::prefix('/customer-address')->name('customer-address.')->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:' . Constants::customer_guard])->group(function () {
        // Get all addresses of the authenticated user
        Route::get('/list', [CustomerAddressController::class, 'getUserAdresses'])->name('list');

        // Show a specific address by ID
        Route::get('/{id}', [CustomerAddressController::class, 'show'])->name('show');

        // Create a new address
        Route::post('/create', [CustomerAddressController::class, 'create'])->name('create');

        // Update an existing address by ID
        Route::put('/{id}', [CustomerAddressController::class, 'update'])->name('update');

        // Delete an address by ID
        Route::delete('/{id}', [CustomerAddressController::class, 'destroy'])->name('destroy');
    });
});
