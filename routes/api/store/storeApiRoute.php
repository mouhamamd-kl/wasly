<?php

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Store\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function () {
    return ('hello from the Product');
});
// Resource routes for basic CRUD operations
Route::middleware(['auth:sanctum', 'abilities:' . Constants::store_owner_guard])->group(function () {
    Route::post('/', [StoreController::class, 'create'])->name('stores.create');
    Route::put('/{id}', [StoreController::class, 'update'])->name('stores.update');
    Route::delete('/{id}', [StoreController::class, 'destroy'])->name('stores.destroy');
});
// Custom routes
Route::get('/', [StoreController::class, 'index'])->name('stores.index');
Route::get('/latest', [StoreController::class, 'latest'])->name('stores.latest');
Route::get('/search', [StoreController::class, 'searchApi'])->name('stores.search'); // API-specific search
Route::get('/nearbyyy', function () {
    return ApiResponse::sendResponse(404, 'Customer not found');
})->name('stores.nearby');
Route::get('/popular/orders', [StoreController::class, 'popularByOrdersApi'])->name('stores.popular.orders');
Route::get('/popular/ratings', [StoreController::class, 'popularByRatingsApi'])->name('stores.popular.ratings');

Route::get('/{id}', [StoreController::class, 'show'])->name('stores.show');
Route::get('/{storeId}/products', [ProductController::class, 'getStoreProductsApi'])->name('products.paginated');
