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
use Telegram\Bot\Laravel\Facades\Telegram;




Route::get('/', function () {
    return view('landing.index');
})->name('home.landing');


Route::prefix('admin')->name('admin.')->middleware(AdminMiddleWare::class)->group(function () {
    Route::get('/', BackHomeController::class)->name('index');
    Route::withoutMiddleware(AdminMiddleWare::class)->group(function () {
        require __DIR__ . '/adminAuth.php';
    });
});
