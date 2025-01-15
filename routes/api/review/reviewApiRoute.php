<?php

use App\Constants\Constants;
use App\Http\Controllers\Api\Review\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('/review')->name('review.')->group(function () {
    Route::middleware(['auth:sanctum', 'abilities:' . Constants::customer_guard])->group(function () {
        // Add a review for a product
        Route::post('/add/{productId}', [ReviewController::class, 'addReview'])->name('add');

        // Update an existing review
        Route::put('/update/{id}', [ReviewController::class, 'updateReview'])->name('update');

        // Remove a review
        Route::delete('/remove/{id}', [ReviewController::class, 'removeReview'])->name('remove');
    });

    Route::get('/product/{productId}', [ReviewController::class, 'getProductReviewsApi'])->name('product.reviews');
    Route::get('/store/{storeId}', [ReviewController::class, 'getStoreReviews'])->name('store.reviews');
});
