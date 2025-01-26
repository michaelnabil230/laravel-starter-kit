<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UploadRequest;
use App\Http\Resources\MediaResource;
use App\Models\TemporaryUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

final class FileUploadController
{
    /**
     * Handles the upload of a file as a single upload.
     */
    public function __invoke(UploadRequest $request): JsonResponse
    {
        try {
            $temporaryUpload = TemporaryUpload::createForFile(
                $file = $request->file('file'),
                Session::getId(),
                (string) Str::uuid(),
                $file->getClientOriginalName()
            );
        } catch (Throwable $throwable) {
            report($throwable);

            throw ValidationException::withMessages(['file' => 'Could not handle upload. Make sure you are uploading a valid file.']);
        }

        return response()->json([
            'media' => MediaResource::make($temporaryUpload->getFirstMedia()),
        ]);
    }
}
