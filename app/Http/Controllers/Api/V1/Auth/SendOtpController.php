<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\SendOtpRequest;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class SendOtpController
{
    /**
     * Request OTP for login/register.
     */
    public function __invoke(SendOtpRequest $request, OtpService $otpService): JsonResponse|Response
    {
        $user = User::query()
            ->firstWhere('phone', $request->phone);

        $sent = $otpService->sendOtp($user);

        if (! $sent) {
            return response()->json([
                'message' => 'Please wait before requesting another OTP.',
            ], 429);
        }

        return response()->noContent();
    }
}
