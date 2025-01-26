<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CheckVersionController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'version' => ['required'],
            'platform' => ['required', 'in:android,ios'],
        ]);

        $currentVersion = config('apps.version');

        $forceUpdate = version_compare(
            $currentVersion,
            $request->version,
            '<>',
        );

        return response()->json([
            'force_update' => $forceUpdate,
            'current_version' => $currentVersion,
        ]);
    }
}
