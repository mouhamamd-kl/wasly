<?php

use App\Constants\constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\CustomerApiAuth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerApiAuth\CustomEmailVerificationController;
use App\Http\Controllers\CustomerApiAuth\EmailVerificationNotificationController;
use App\Http\Controllers\CustomerApiAuth\NewPasswordController;
use App\Http\Controllers\CustomerApiAuth\PasswordResetLinkController;
use App\Http\Controllers\CustomerApiAuth\RegisteredUserController;
use App\Http\Controllers\CustomerApiAuth\VerifyEmailController;
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

// Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
//     ->name('password.reset');

// Route::post('reset-password', [NewPasswordController::class, 'store'])
//     ->name('password.store');

// Route::get('verify-email', [CustomEmailVerificationController::class, 'notice'])->name('verification.notice');
// Route::get('verify-email/{id}/{token}', [CustomEmailVerificationController::class, 'verify'])
//     ->name('verification.verify');
// Route::post('email/verification-notification', [CustomEmailVerificationController::class, 'resend'])
//     ->name('verification.send');
// // Route::get('verify-email/{id}/{token}', [CustomEmailVerificationController::class, 'verify'])->name('testo.test');
// Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
//     ->middleware(['auth:sanctum', 'abilities:customer'])
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

Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');

Route::get('verify-email', [CustomEmailVerificationController::class, 'notice'])->name('verification.notice');
Route::get('verify-email/{id}/{token}', [CustomEmailVerificationController::class, 'verify'])
    ->name('verification.verify');
Route::post('email/verification-notification', [CustomEmailVerificationController::class, 'resend'])
    ->name('verification.send');
// Route::get('verify-email/{id}/{token}', [CustomEmailVerificationController::class, 'verify'])->name('testo.test');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'abilities:'.constants::customer_guard])
    ->name('logout');
