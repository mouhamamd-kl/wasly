<?php

use App\Constants\constants;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Customer\CustomerController;

Route::middleware(['auth:sanctum', 'abilities:' . constants::customer_guard])->group(function () {

    Route::patch('/{id}', [CustomerController::class, 'update']);
    Route::delete('/{id}', [CustomerController::class, 'destroy']);
    Route::get('/info', [CustomerController::class, 'info']);
});

require __DIR__ . '/customerApiAuth.php';
