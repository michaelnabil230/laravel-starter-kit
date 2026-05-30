<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Resources\Dashboard\AuthResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Uri;
use Inertia\Middleware;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'layouts.dashboard.app';

    /**
     * Define the props that are shared once and remembered across navigations.
     *
     * @return array<string, callable|OnceProp>
     */
    public function shareOnce(Request $request): array
    {
        return [
            ...parent::shareOnce($request),
            'appName' => fn (): string => config('app.name', 'Laravel'),
            'timezone' => fn (): string => config('app.timezone', 'UTC'),
            'supportedLocales' => fn (): array => Arr::map(
                LaravelLocalization::getSupportedLocales(),
                fn (array $properties, string $locale): array => [
                    ...$properties,
                    'url' => LaravelLocalization::getLocalizedURL($locale, route('dashboard.welcome')),
                ],
            ),
            'locale' => fn (): string => LaravelLocalization::getCurrentLocale(),
            'translations' => fn (): array => __('dashboard', []),
            'environment' => fn (): string => app()->environment(),
        ];
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /** @var ?\App\Models\Admin $admin */
        $admin = auth()->user();

        return [
            ...parent::share($request),
            'auth' => [
                'admin' => $admin ? AuthResource::make($admin) : null,
                'can' => $admin ? ['owner' => true] : [],
            ],
            'toast' => session('toast'),
        ];
    }

    /**
     * Defines a callback that returns the relative URL.
     */
    public function urlResolver(): Closure
    {
        return function (HttpRequest $request): string {
            $url = Uri::of($request->url())
                ->withQuery($request->query())
                ->value();

            return rawurldecode($url);
        };
    }
}
