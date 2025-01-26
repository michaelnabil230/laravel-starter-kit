<?php

declare(strict_types=1);

namespace App\View\Components\Dashboard;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

final class App extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        $config = [
            'appName' => config('app.name', 'Laravel'),
            'timezone' => config('app.timezone', 'UTC'),
            'supportedLocales' => Arr::map(
                LaravelLocalization::getSupportedLocales(),
                fn (array $properties, string $locale): array => [
                    ...$properties,
                    'url' => LaravelLocalization::getLocalizedURL($locale, route('dashboard.welcome')),
                ],
            ),
            'locale' => $locale = LaravelLocalization::getCurrentLocale(),
            'translations' => $this->getTranslations($locale),
            'sentry' => [
                'dsn' => config('sentry.dsn'),
                'tunnel' => config('sentry-tunnel.tunnel-url'),
                'release' => config('sentry.release'),
                'environment' => config('sentry.environment'),
                'sendDefaultPii' => config('sentry.send_default_pii'),
                'tracesSampleRate' => config('sentry.traces_sample_rate'),
            ],
        ];

        return view('components.dashboard.app', ['config' => $config]);
    }

    /**
     * Get the translations for the given locale.
     *
     * @return array<string, string>
     */
    protected function getTranslations(string $locale): array
    {
        if (File::exists(lang_path("$locale/dashboard.json"))) {
            return File::json(lang_path("$locale/dashboard.json"));
        }

        return [];
    }
}
