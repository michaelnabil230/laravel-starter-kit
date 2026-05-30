<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::name('dashboard.')
    ->prefix('dashboard')
    ->middleware(['web', 'auth'])->group(function (): void {});
