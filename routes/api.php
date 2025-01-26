<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CheckVersionController;
use App\Http\Controllers\Api\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->middleware('localize')->group(function () {
    // Check Version
    Route::post('/check-version', CheckVersionController::class)->name('check-version');

    // File Upload
    Route::post('/file-upload', FileUploadController::class)->name('file-upload');

    require __DIR__.'/apis/v1.php';
});
