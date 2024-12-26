<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\Admin\BackHomeController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\AdminMiddleWare;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\StoreOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;


Route::post('/telegram/webhook', function (Request $request) {
    $update = $request->all();

    if (isset($update['message'])) {
        $chatId = $update['message']['chat']['id'];
        $text = $update['message']['text'] ?? null;

        if ($text === '/start') {
            // Send a welcome message
            Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                'chat_id' => $chatId,
                'text' => "Welcome to Wasly Verification Bot! Use /register to get started.",
            ]);
        } elseif ($text === '/register') {
            // Request user's phone number
            Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                'chat_id' => $chatId,
                'text' => "Please share your phone number to proceed with registration.",
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
        } elseif (isset($update['message']['contact'])) {
            // Handle the shared contact
            $phoneNumber = $update['message']['contact']['phone_number'];

            // Check models for the phone number
            $user = Customer::where('phone_number', $phoneNumber)->first() ??
                    Delivery::where('phone_number', $phoneNumber)->first() ??
                    StoreOwner::where('phone_number', $phoneNumber)->first();

            if ($user) {
                // Update chat_id for the user
                $user->chat_id = $chatId;
                $user->save();

                // Send a success message
                Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => "Thank you! You have been successfully registered.",
                ]);
            } else {
                // Send a failure message
                Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => "Sorry, we could not find your phone number in our records.",
                ]);
            }
        }
    }

    return response()->json(['status' => 'ok']);
});
Route::get('/', function () {
    return view('landing.index');
})->name('home.landing');

Route::prefix('admin')->name('admin.')->middleware(AdminMiddleWare::class)->group(function () {
    // Route::get('/', BackHomeController::class)->name('index');
    Route::withoutMiddleware(AdminMiddleWare::class)->group(function () {
        require __DIR__ . '/adminAuth.php';
    });
});
