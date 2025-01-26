<?php

declare(strict_types=1);

namespace App\Bootstrap;

use Illuminate\Foundation\Configuration\Middleware;

final class MiddlewareBootstrapper
{
    public function __invoke(Middleware $middleware): void
    {
        $middleware
            ->web(append: [
                \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            ])
            ->alias([
                'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
                'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
                'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
                'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
                'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
            ])
            ->priority([
                \Illuminate\Auth\Middleware\Authenticate::class,
                \App\Http\Middleware\HandleInertiaRequests::class,
            ])
            ->redirectTo(
                guests: fn (): string => route('dashboard.login'),
                users: fn (): string => route('welcome'),
            );
    }
}
