<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\VerifyOtpRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class VerifyOtpController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(VerifyOtpRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::query()->firstWhere('phone', $request->phone);

        throw_unless($user->verifyOtp((string) $request->otp_code), ValidationException::withMessages([
            'otp_code' => __('auth.otp_code'),
        ]));

        $user->markAsVerified();

        $user->update([
            'device_type' => $request->device_type,
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
