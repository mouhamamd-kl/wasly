<?php

use App\Constants\constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Store\StoreController;
use App\Http\Controllers\Api\StoreOwner\StoreOwnerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::middleware(['auth:sanctum', 'abilities:' . constants::store_owner_guard])->group(function () {
    Route::patch('/{id}', [StoreOwnerController::class, 'update']);
    Route::delete('/{id}', [StoreOwnerController::class, 'destroy']);
    Route::get('/info', [StoreOwnerController::class, 'info']);
});
require __DIR__ . '/storeOwnerapiAuth.php';

