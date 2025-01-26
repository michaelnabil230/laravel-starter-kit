<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Resources\V1\UserResource;
use Illuminate\Http\JsonResponse;

final class UserController
{
    public function __invoke(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();

        return response()->json([
            'user' => UserResource::make($user),
        ]);
    }
}
