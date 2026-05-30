<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

final class OtpService
{
    /**
     * OtpService constructor.
     */
    public function __construct(protected WhatsAppService $whatsAppService) {}

    /**
     * Generate and send OTP to user.
     */
    public function sendOtp(User $user): bool
    {
        if (! $user->canResendOtp()) {
            return false;
        }

        $otp = $user->generateOtp();

        if (app()->isLocal()) {
            Log::info("OTP for user {$user->id}: {$otp}");

            return true;
        }

        return $this->whatsAppService->sendOtp($user->phone, $otp);
    }
}
