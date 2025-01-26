<!DOCTYPE html>
<html
    dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}"
    lang="{{ app()->getLocale() }}"
    class="scroll-smooth selection:bg-blue-700 selection:text-white"
>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="title" content="App" />
        <meta name="description" content="@lang('front.meta.description')" />
        <meta name="keywords" content="App" />
        <meta name="distribution" content="global" />
        <meta name="language" content="{{ LaravelLocalization::getCurrentLocale() }}" />
        <meta property="og:owners" content="App" />
        <meta name="author" content="App" />
        <meta name="publisher" content="App" />
        <meta name="copyright" content="{{ now()->rawFormat('Y') }} App" />
        <meta name="theme-color" content="#1845e7" />

        <!-- Meta Tags -->
        <meta property="og:url" content="{{ config('app.url') }}" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="App" />
        <meta property="og:description" content="@lang('front.meta.description')" />
        <meta property="og:image" content="{{ asset('meta/og.jpg') }}" />
        <meta property="og:image:url" content="{{ asset('meta/og.jpg') }}" />
        <meta property="og:image:alt" content="App" />

        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta property="twitter:domain" content="{{ Str::replace(['https://', 'http://'], '', config('app.url')) }}" />
        <meta property="twitter:url" content="{{ config('app.url') }}" />
        <meta name="twitter:title" content="App" />
        <meta name="twitter:description" content="@lang('front.meta.description')" />
        <meta name="twitter:image:alt" content="App" />
        <meta name="twitter:image" content="{{ asset('meta/og.jpg') }}" />
        <meta name="twitter:widgets:csp" content="on" />

        <!-- Ios Meta Tags -->
        <meta property="al:ios:app_name" content="App" />
        <meta property="al:ios:app_store_id" content="{id}" />
        <meta name="apple-itunes-app" content="app-id={id}, app-argument={{ config('app.url') }}" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="apple-mobile-web-app-title" content="App" />

        <!-- Android Meta Tags -->
        <meta property="al:android:app_name" content="App" />
        <meta property="al:android:package" content="{package}" />
        <meta name="mobile-web-app-capable" content="yes" />
        <meta name="google-play-app" content="app-id={package}" />

        @vite('resources/js/front/app.js')

        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link rel="icon" type="image/x-icon" href="{{ asset('logo.ico') }}" />

        <title>App</title>
    </head>

    <body x-data="{ mobileMenuIsOpen: false }">
        <x-front.toast />

        {{ $slot }}
    </body>
</html>
