<?php

declare(strict_types=1);

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::prefix(LaravelLocalization::setLocale())
    ->middleware(['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'])
    ->group(function () {
        Livewire::setUpdateRoute(fn (array|string|callable|null $handle) => Route::post('/livewire/update', $handle));

        Route::get('health', HealthCheckResultsController::class);

        Route::view('/', 'welcome')->name('welcome');

        Route::view('/apps', 'apps');

        Route::get('/{page}', PageController::class)
            ->name('page')
            ->whereIn('page', ['privacy', 'terms-and-conditions']);

        require __DIR__.'/dashboard.php';
    });
