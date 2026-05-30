<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

final class SmsService
{
    /**
     * Send SMS message.
     */
    public function send(string $phone, string $message): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('services.sms.token'),
        ])->post(config('services.sms.endpoint'), [
            'to' => $phone,
            'message' => $message,
        ]);

        return $response->successful();
    }
}
