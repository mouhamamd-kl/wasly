<?php

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function () {
    return ('hello from the Product');
});
Route::get('/', [ProductController::class, 'index']);

// Route to get the latest products
Route::get('/latest', [ProductController::class, 'latestApi']);

// Route to search for products (search API)
Route::post('/search', [ProductController::class, 'searchApi']);

Route::get('/popular', [ProductController::class, 'getMostPopularProducts']);
// Route to get a single product by ID (show)
Route::get('/{id}', [ProductController::class, 'show']);
Route::middleware(['auth:sanctum', 'abilities:' . Constants::store_owner_guard])->group(function () {
    // Route to create a new product
    Route::post('/', [ProductController::class, 'create']);

    // Route to update an existing product by ID
    Route::put('/{id}', [ProductController::class, 'update']);

    // Route to delete a product by ID
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

