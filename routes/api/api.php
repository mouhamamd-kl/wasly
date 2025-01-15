<?php

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Customer\CustomerController;
use App\Http\Controllers\Api\Telegram\TelegramVerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Services\Telegram;
Route::get('/test',function(){
    return 'hello';
});
Route::prefix('/customer')->name('customer.')->group(function () {
    // Routes for customers
    // Route::middleware(['auth:sanctum', 'abilities:'.Constants::customer_guard])->get('/test', function (Request $request) {
    //     return ApiResponse::sendResponse(code: 200, msg: 'user info', data: $request->user());
    // });
    
    require __DIR__ . '/customer/cutomerApiRoute.php';
});

Route::prefix('/delivery')->name('delivery.')->group(function () {
    // Routes for delivery personnel
    Route::middleware(['auth:sanctum', 'abilities:' . Constants::delivery_guard])->get('/test', function (Request $request) {
        return ApiResponse::sendResponse(code: 200, msg: 'user info', data: $request->user());
    });
    require __DIR__ . '/delivery/deliveryApiRoute.php';
});

Route::prefix('/store-owner')->name('storeOwner.')->group(function () {
    // Routes for store owners
    Route::middleware(['auth:sanctum', 'abilities:' . Constants::store_owner_guard])->get('/test', function (Request $request) {
        return ApiResponse::sendResponse(code: 200, msg: 'user info', data: $request->user());
    });
    require __DIR__ . '/storeOwner/storeOwnerApiRoute.php';
});

Route::prefix('/product')->name('product.')->group(function(){
    require __DIR__ . '/product/productApiRoute.php';
});
// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return ApiResponse::sendResponse(code: 200, msg: 'user info', data: $request->user());
// });
// require __DIR__ . '/apiAuth.php';
