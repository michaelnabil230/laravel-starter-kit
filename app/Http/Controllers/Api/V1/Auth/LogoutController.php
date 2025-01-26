<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Response;

final class LogoutController
{
    public function __invoke(): Response
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();
        $user->update([
            'fcm_token' => null,
            'api_token' => null,
        ]);

        return response()->noContent();
    }
}
