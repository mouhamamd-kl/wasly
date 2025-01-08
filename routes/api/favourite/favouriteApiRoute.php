<?php

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Favourite\FavouriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'abilities:' . Constants::customer_guard])->group(function () {
    Route::get('/test', function (Request $request) {
        return ApiResponse::sendResponse(code:200,msg:'test');
    });
    Route::post('/add-to-favourites/{productId}', [FavouriteController::class, 'addToFavourites'])->name('addToFavourites');
    Route::delete('/remove-from-favourites/{productId}', [FavouriteController::class, 'removeFromFavourites'])->name('removeFromFavourites');
    Route::get('/user-favourites', [FavouriteController::class, 'getUserFavouriteProductsApi'])->name('getUserFavourites');
    Route::post('/clear-favourites', [FavouriteController::class, 'clearUserFavouriteProducts'])->name('clearFavourites');
});
