<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CheckVersionController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->middleware('localize')->group(function () {
    // Check Version
    Route::post('/check-version', CheckVersionController::class);

    require __DIR__.'/apis/v1.php';
});
