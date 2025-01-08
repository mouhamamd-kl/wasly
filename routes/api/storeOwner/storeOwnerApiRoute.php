<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Store\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/stores/nearby', [StoreController::class, 'nearby']);
Route::get('/stores/popular/orders', [StoreController::class, 'popularByOrders']);
Route::get('/stores/popular/ratings', [StoreController::class, 'popularByRatings']);
Route::get('/',function(){
    return ('hello from the Store Owner');
});
require __DIR__ . '/storeOwnerapiAuth.php';

