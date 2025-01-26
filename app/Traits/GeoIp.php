<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

trait GeoIp
{
    protected function getCountryCodeForIp(string $ip): string
    {
        return Cache::remember("country-code-{$ip}", 60 * 5, function () use ($ip) {
            $response = Http::get("https://freegeoip.app/json/{$ip}");

            if ($response->status() !== 200) {
                return 'US';
            }

            return $response->json('country_code', 'US');
        });
    }

    protected function getTimezoneForIp(string $ip): string
    {
        $default = config('app.timezone');

        return Cache::remember("time-zone-{$ip}", 60 * 5, function () use ($ip, $default) {
            $response = Http::get("https://freegeoip.app/json/{$ip}");

            if ($response->status() !== 200) {
                return $default;
            }

            $timezone = $response->json('time_zone', $default);

            if (empty($timezone)) {
                return $default;
            }

            return $timezone;
        });
    }
}
