<?php

declare(strict_types=1);

namespace App\View\Components\Dashboard;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

final class InitialSharedData extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        $data = [
            'appName' => config('app.name', 'Laravel'),
            'timezone' => config('app.timezone', 'UTC'),
            'supportedLocales' => Arr::map(
                LaravelLocalization::getSupportedLocales(),
                fn (array $properties, string $locale): array => [
                    ...$properties,
                    'url' => LaravelLocalization::getLocalizedURL($locale, route('dashboard.welcome')),
                ],
            ),
            'locale' => LaravelLocalization::getCurrentLocale(),
            'translations' => __('dashboard', []),
            'sentry' => [
                'dsn' => config('sentry.dsn'),
                'release' => config('sentry.release'),
                'environment' => config('sentry.environment'),
                'sendDefaultPii' => config('sentry.send_default_pii'),
                'tracesSampleRate' => config('sentry.traces_sample_rate'),
            ],
            'environment' => app()->environment(),
        ];

        return view('components.dashboard.initial-shared-data', ['data' => $data]);
    }
}
