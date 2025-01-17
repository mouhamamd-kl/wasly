<?php

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\StoreOwnerApiAuth\AuthenticatedSessionController;
use App\Http\Controllers\StoreOwnerApiAuth\CustomEmailVerificationController;
use App\Http\Controllers\StoreOwnerApiAuth\EmailVerificationNotificationController;
use App\Http\Controllers\StoreOwnerApiAuth\NewPasswordController;
use App\Http\Controllers\StoreOwnerApiAuth\PasswordResetLinkController;
use App\Http\Controllers\StoreOwnerApiAuth\RegisteredUserController;
use App\Http\Controllers\StoreOwnerApiAuth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// Route::post('/register', [RegisteredUserController::class, 'store'])
//     ->middleware('guest')
//     ->name('register');

// Route::post('/login', [AuthenticatedSessionController::class, 'store'])
//     ->middleware('guest')
//     ->name('login');

// Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//     ->middleware('guest')
//     ->name('password.email');

// Route::post('/reset-password', [NewPasswordController::class, 'store'])
//     ->middleware('guest')
//     ->name('password.store');

// Route::get('verify-email', [CustomEmailVerificationController::class, 'notice'])->name('verification.notice');
// Route::get('verify-email/{id}/{token}', [CustomEmailVerificationController::class, 'verify'])
//     ->name('verification.verify');
// Route::post('email/verification-notification', [CustomEmailVerificationController::class, 'resend'])
//     ->name('verification.send');
// Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
//     ->middleware('auth:store_owner')
//     ->name('logout');
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('verify-email', [CustomEmailVerificationController::class, 'notice'])->name('verification.notice');
Route::get('verify-email/{id}/{token}', [CustomEmailVerificationController::class, 'verify'])
    ->name('verification.verify');
Route::post('email/verification-notification', [CustomEmailVerificationController::class, 'resend'])
    ->name('verification.send');
// Route::get('verify-email/{id}/{token}', [CustomEmailVerificationController::class, 'verify'])->name('testo.test');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'abilities:' . Constants::store_owner_guard])
    ->name('logout');
