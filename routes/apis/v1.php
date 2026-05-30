<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\DeleteAccountController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\SendOtpController;
use App\Http\Controllers\Api\V1\Auth\UpdateUserController;
use App\Http\Controllers\Api\V1\Auth\UserController;
use App\Http\Controllers\Api\V1\Auth\VerifyOtpController;
use App\Http\Controllers\Api\V1\MainController;
use App\Http\Controllers\Api\V1\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('V1')->name('v1.')->group(function (): void {
    // Auth
    Route::prefix('auth')->name('auth.')->group(function (): void {

        Route::post('send-otp', SendOtpController::class);

        Route::post('register', RegisterController::class);

        Route::post('verify-otp', VerifyOtpController::class)->name('verify-otp');

        Route::middleware('auth:api')->group(function (): void {
            Route::get('user', UserController::class);

            Route::post('logout', LogoutController::class);

            Route::post('update', UpdateUserController::class);

            Route::delete('delete', DeleteAccountController::class);
        });
    });

    Route::middleware('auth:api')->group(function (): void {
        // Main
        Route::get('/main', MainController::class);

        // Notifications
        Route::get('/notifications', NotificationController::class);
    });
});
