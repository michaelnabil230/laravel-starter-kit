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

        <link rel="icon" type="image/x-icon" href="{{ asset('logo.ico') }}" />

        <title>App Apps</title>
    </head>

    <body>
        <script type="text/javascript">
            window.onload = () => {
                var userAgent = navigator.userAgent || navigator.vendor || window.opera;

                if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                    window.location.href = '{{ config('apps.ios') }}';
                } else if (/android/i.test(userAgent)) {
                    window.location.href = '{{ config('apps.android') }}';
                } else {
                    window.location.href = '{{ config('app.url') }}';
                }
            };
        </script>
    </body>
</html>
