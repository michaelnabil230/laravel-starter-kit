<?php

declare(strict_types=1);

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Dashboard\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Dashboard\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Dashboard\Auth\NewPasswordController;
use App\Http\Controllers\Dashboard\Auth\PasswordController;
use App\Http\Controllers\Dashboard\Auth\PasswordResetLinkController;
use App\Http\Controllers\Dashboard\Auth\VerifyEmailController;
use App\Http\Controllers\Dashboard\ImportExport\UserImportExportController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\SearchController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::middleware(App\Http\Middleware\HandleInertiaRequests::class)
    ->prefix('/dashboard')
    ->name('dashboard.')
    ->group(function (): void {
        Route::middleware('guest')->group(function (): void {
            Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

            Route::post('/login', [AuthenticatedSessionController::class, 'store']);

            Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');

            Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

            Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');

            Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
        });

        Route::middleware('auth')->group(function (): void {
            Route::get('/verify-email', EmailVerificationPromptController::class)->name('verification.notice');

            Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

            Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

            Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');

            Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

            Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

            Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

            Route::controller(ProfileController::class)
                ->prefix('profile')
                ->name('profile.')
                ->group(function (): void {
                    Route::get('/', 'edit')->name('edit');
                    Route::put('/', 'update')->name('update');
                    Route::delete('/', 'destroy')->name('destroy');
                    Route::delete('/photo', 'destroyProfilePhoto')->name('photo.destroy');
                });

            Route::get('/', WelcomeController::class)->name('welcome');

            Route::get('/search', SearchController::class)->name('search');

            // Home
            Route::get('/', WelcomeController::class)->name('welcome');

            // Users
            Route::prefix('users')->name('users.')->controller(UserController::class)->group(function (): void {
                Route::delete('/pluck-destroy', 'pluckDestroy')->name('pluck-destroy');

                Route::controller(UserImportExportController::class)->group(function (): void {
                    Route::post('/export', 'export')->name('export');
                    Route::post('/import', 'import')->name('import');
                });
            });
            Route::resource('users', UserController::class);

            // Admins
            Route::prefix('admins')->name('admins.')->controller(AdminController::class)->group(function (): void {
                Route::delete('/pluck-destroy', 'pluckDestroy')->name('pluck-destroy');
            });
            Route::resource('admins', AdminController::class)->except('show');
        });
    });
