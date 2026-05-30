<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

final class WhatsAppService
{
    /**
     * Send WhatsApp OTP message.
     */
    public function sendOtp(string $phone, int $otp): bool
    {
        $url = sprintf(
            'https://live-mt-server.wati.io/%s/api/v2/sendTemplateMessage',
            config('services.wati.tenant_id')
        );

        $response = Http::withToken(config('services.wati.api_token'))
            ->acceptJson()
            ->post($url, [
                'whatsappNumber' => $phone,
                'template_name' => 'otp',
                'broadcast_name' => 'otp',
                'parameters' => [
                    [
                        'name' => '1',
                        'value' => $otp,
                    ],
                ],
            ]);

        return $response->successful();
    }
}
