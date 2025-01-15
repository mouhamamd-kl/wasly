<?php

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\Customer\CustomerController;
use App\Http\Controllers\Api\Telegram\TelegramVerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\StoreOwner;
use App\Services\Telegram;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isEmpty;

Route::get('/test', function () {
    return 'hello';
});
Route::prefix('/customer')->name('customer.')->group(function () {
    // Routes for customers
    // Route::middleware(['auth:sanctum', 'abilities:'.Constants::customer_guard])->get('/test', function (Request $request) {
    //     return ApiResponse::sendResponse(code: 200, msg: 'user info', data: $request->user());
    // });

    require __DIR__ . '/customerAddress/customerAdressApiRoute.php';
    require __DIR__ . '/favourite/favouriteApiRoute.php';
    require __DIR__ . '/customer/cutomerApiRoute.php';
    require __DIR__ . '/customerCard/customerCardApiRoute.php';
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

Route::prefix('/product')->name('product.')->group(function () {
    require __DIR__ . '/product/productApiRoute.php';
});
Route::prefix('/store')->name('store.')->group(function () {
    require __DIR__ . '/store/storeApiRoute.php';
});
Route::prefix('/cart')->name('cart.')->group(function () {
    require __DIR__ . '/cart/cartApiRoute.php';
});
Route::prefix('/category')->name('category.')->group(function () {
    require __DIR__ . '/category/categoryApiRoute.php';
});
Route::prefix('/order')->name('order.')->group(function () {
    require __DIR__ . '/order/orderApiRoute.php';
});
Route::prefix('/review')->name('review.')->group(function () {
    require __DIR__ . '/review/reviewApiRoute.php';
});

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return ApiResponse::sendResponse(code: 200, msg: 'user info', data: $request->user());
// });
// require __DIR__ . '/apiAuth.php';

Route::post('/telegram/webhook', function (Request $request) {

    $update = $request->all();
    $text = $update['message']['text'];
    $chatId = $update['message']['chat']['id'];
    if ($text == '/start') {
        $customer = Customer::where('chat_id', $chatId)->get();
        $delivery = Delivery::where('chat_id', $chatId)->get();
        $storeOwner = StoreOwner::where('chat_id', $chatId)->get();
        if ($customer . isEmpty() == false || $delivery . isEmpty() == false || $storeOwner . isEmpty() == false) {
            // Send a message indicating the user is already registered
            Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                'chat_id' => $chatId,
                'text' => "You are already registered in our system. Thank you for being with us!",
            ]);
        } else {
            // Show button to request the user's phone number
            Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                'chat_id' => $chatId,
                'text' => "Welcome to Wasly Verification Bot! Please share your phone number to proceed.",
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [[
                            'text' => "Share My Phone Number",
                            'request_contact' => true,
                        ]],
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ]),
            ]);
        }
    } elseif ($update['message']['contact']) {
        $phoneNumber = $update['message']['contact']['phone_number'];

        Log::info('Contact Shared:', ['phone_number' => $phoneNumber]);
        Log::info('Incoming Request:', $request->all());

        // Check each model individually for the phone number and update chat_id if found
        $customer = Customer::where('phone_number', $phoneNumber)->get();

        $delivery = Delivery::where('phone_number', $phoneNumber)->get();
        $storeOwner = StoreOwner::where('phone_number', $phoneNumber)->get();

        if (!$customer . isEmpty()) {
            $customer->chat_id = $chatId;
            $customer->save();
        }
        if (!$delivery . isEmpty()) {
            $delivery->chat_id = $chatId;
            $delivery->save();
        }
        if (!$storeOwner . isEmpty()) {
            $storeOwner->chat_id = $chatId;
            $storeOwner->save();
        }

        if ($customer . isEmpty() == false || $delivery . isEmpty() == false || $storeOwner . isEmpty() == false) {
            Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                'chat_id' => $chatId,
                'text' => "Thank you for sharing your phone number! Your account has been successfully linked to your Telegram. Enjoy our services!",
            ]);
        } else {
            Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                'chat_id' => $chatId,
                'text' => "Sorry, we could not find your phone number in our records. Please contact support.",
            ]);
        }
    } else {
        Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
            'chat_id' => $chatId,
            'text' => "Please use /start command ",
        ]);
    }
});
