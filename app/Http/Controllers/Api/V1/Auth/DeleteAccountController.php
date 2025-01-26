<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class DeleteAccountController
{
    public function __invoke(Request $request): Response
    {
        $request->validate([
            'password' => ['required', 'current_password:api'],
        ]);

        /** @var \App\Models\User $user */
        $user = auth('api')->user();
        $user->delete();

        return response()->noContent();
    }
}
