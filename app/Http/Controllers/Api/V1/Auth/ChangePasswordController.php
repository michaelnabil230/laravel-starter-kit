<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\ChangePasswordRequest;
use Illuminate\Http\Response;

final class ChangePasswordController
{
    public function __invoke(ChangePasswordRequest $request): Response
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();

        $user->update(['password' => $request->password]);

        return response()->noContent();
    }
}
