<?php

use App\Constants\Constants;
use App\Http\Controllers\Api\Favourite\FavouriteController;
use Illuminate\Support\Facades\Route;

Route::prefix('/favourites')->name('favourites.')->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:' . Constants::customer_guard])->group(function () {
        // Add a product to favourites
        Route::post('/add/{productId}', [FavouriteController::class, 'addToFavourites'])->name('add');

        // Remove a product from favourites
        Route::delete('/remove/{productId}', [FavouriteController::class, 'removeFromFavourites'])->name('remove');

        // Get all favourite products of the authenticated user
        Route::get('/list', [FavouriteController::class, 'getUserFavouriteProductsApi'])->name('list');

        // Clear all favourite products for the authenticated user
        Route::post('/clear', [FavouriteController::class, 'clearUserFavouriteProducts'])->name('clear');
    });
});
