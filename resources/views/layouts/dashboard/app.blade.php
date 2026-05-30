<!DOCTYPE html>
<html
    lang="{{ LaravelLocalization::getCurrentLocale() }}"
    dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}"
    @class (['dark' => ($appearance ?? 'system') == 'dark'])
>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Whatsapp" />
    <meta name="description" content="@lang('front.meta.description')" />
    <meta name="keywords" content="Whatsapp" />
    <meta name="distribution" content="global" />
    <meta name="language" content="{{ LaravelLocalization::getCurrentLocale() }}" />
    <meta property="og:owners" content="Whatsapp" />
    <meta name="author" content="Whatsapp" />
    <meta name="publisher" content="Whatsapp" />
    <meta name="copyright" content="{{ now()->rawFormat('Y') }} Whatsapp" />
    <meta name="theme-color" content="#1845e7" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Meta Tags -->
    <meta property="og:url" content="{{ config('app.url') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Whatsapp" />
    <meta property="og:description" content="@lang('front.meta.description')" />
    <meta property="og:image" content="{{ asset('meta/og.jpg') }}" />
    <meta property="og:image:url" content="{{ asset('meta/og.jpg') }}" />
    <meta property="og:image:alt" content="Whatsapp" />

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta property="twitter:domain" content="{{ Str::replace(['https://', 'http://'], '', config('app.url')) }}" />
    <meta property="twitter:url" content="{{ config('app.url') }}" />
    <meta name="twitter:title" content="Whatsapp" />
    <meta name="twitter:description" content="@lang('front.meta.description')" />
    <meta name="twitter:image:alt" content="Whatsapp" />
    <meta name="twitter:image" content="{{ asset('meta/og.jpg') }}" />
    <meta name="twitter:widgets:csp" content="on" />

    <link rel="icon" type="image/x-icon" href="{{ asset('logo.ico') }}" />

    {{-- Inline script to detect system dark mode preference and apply it immediately --}}
    <script>
        (function () {
            const appearance = '{{ $appearance }}' ?? 'system';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    <!-- Scripts -->
    @routes
    @vite (['resources/js/dashboard/app.ts', "resources/js/dashboard/Pages/{$page['component']}.vue"])
    <title>{{ config('app.name', 'Laravel') }}</title>
    @inertiaHead
</head>

<body class="bg-gray-50 dark:bg-neutral-900">
    @inertia
</body>
</html>
