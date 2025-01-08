<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function () {
    return ('hello from the Product');
});
Route::get('/{categoryId}/products', [ProductController::class, 'getCategoryProductsApi'])->name('products.paginated');
