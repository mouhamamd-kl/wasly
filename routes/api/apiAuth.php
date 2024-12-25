<?php

use App\Helpers\ApiResponse;
use App\Http\Controllers\apiAuth\AuthenticatedSessionController;
use App\Http\Controllers\apiAuth\CustomEmailVerificationController;
use App\Http\Controllers\apiAuth\EmailVerificationNotificationController;
use App\Http\Controllers\apiAuth\NewPasswordController;
use App\Http\Controllers\apiAuth\PasswordResetLinkController;
use App\Http\Controllers\apiAuth\RegisteredUserController;
use App\Http\Controllers\apiAuth\VerifyEmailController;
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
    ->middleware('auth:sanctum')
    ->name('logout');
