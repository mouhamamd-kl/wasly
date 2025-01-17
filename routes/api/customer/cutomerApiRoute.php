<?php

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Customer\CustomerController;

require __DIR__ . '/customerApiAuth.php';
Route::middleware(['auth:sanctum', 'abilities:' . Constants::customer_guard])->group(function () {

    Route::post('/{id}', [CustomerController::class, 'update']);
    Route::delete('/{id}', [CustomerController::class, 'destroy']);
    Route::get('/info', [CustomerController::class, 'info']);
});
