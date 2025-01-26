<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;

final class MainController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([]);
    }
}
