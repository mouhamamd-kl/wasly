<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\DeliveryApiAuth\AuthenticatedSessionController;
use App\Http\Controllers\DeliveryApiAuth\CustomEmailVerificationController;
use App\Http\Controllers\DeliveryApiAuth\EmailVerificationNotificationController;
use App\Http\Controllers\DeliveryApiAuth\NewPasswordController;
use App\Http\Controllers\DeliveryApiAuth\PasswordResetLinkController;
use App\Http\Controllers\DeliveryApiAuth\RegisteredUserController;
use App\Http\Controllers\DeliveryApiAuth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

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
    ->middleware('auth:delivery')
    ->name('logout');
