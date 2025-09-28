<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\ChangePasswordController;
use App\Http\Controllers\Api\V1\Auth\CodeCheckController;
use App\Http\Controllers\Api\V1\Auth\DeleteAccountController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\Auth\UpdateUserController;
use App\Http\Controllers\Api\V1\Auth\UserController;
use App\Http\Controllers\Api\V1\MainController;
use App\Http\Controllers\Api\V1\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('V1')->name('v1.')->group(function (): void {
    // Auth
    Route::prefix('auth')->name('auth.')->group(function (): void {

        Route::post('login', LoginController::class)->name('login');

        Route::prefix('password')->group(function (): void {
            Route::post('/email', ForgotPasswordController::class);
            Route::post('/code/check', CodeCheckController::class);
            Route::post('/reset', ResetPasswordController::class);
        });

        Route::middleware('auth:api')->group(function (): void {
            Route::get('user', UserController::class);

            Route::post('logout', LogoutController::class);

            Route::post('update', UpdateUserController::class);

            Route::delete('delete', DeleteAccountController::class);

            Route::post('change-password', ChangePasswordController::class);
        });
    });

    Route::middleware('auth:api')->group(function (): void {
        // Main
        Route::get('/main', MainController::class);

        // Notifications
        Route::get('/notifications', NotificationController::class);
    });
});
