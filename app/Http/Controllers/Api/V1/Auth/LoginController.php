<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class LoginController
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::firstWhere('email', $request->email);

        if (! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $user->update([
            'fcm_token' => $request->fcm_token,
            'api_token' => hash('sha256', $token = Str::random(60)),
            'last_login_at' => now(),
        ]);

        $user = $user->refresh();

        return response()->json([
            'user' => UserResource::make($user),
            'token' => $token,
        ]);
    }
}
