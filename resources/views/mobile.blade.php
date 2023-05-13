<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" value="{{ csrf_token() }}"/>
        <title>{{ env('APP_NAME') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @if ($app->logo)
            <link rel="apple-touch-icon" sizes="180x180" href="{{ $app->logo->m }}">
            <link rel="icon" type="image/png" sizes="32x32" href="{{ $app->logo->s }}">
        @else
            <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        @endif

        <script>
            window.storeInfo = {!! json_encode($app) !!};

            (function() {
                var defaultThemeMode = "system";
                var themeMode;

                if (document.documentElement) {
                    if (document.documentElement.hasAttribute("data-theme-mode")) {
                        themeMode = document.documentElement.getAttribute("data-theme-mode");
                    } else {
                        if (localStorage.getItem("data-theme") !== null) {
                            themeMode = localStorage.getItem("data-theme");
                        } else {
                            themeMode = defaultThemeMode;
                        }
                    }
                    if (themeMode === "system") {
                        themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                    }
                    document.documentElement.setAttribute("data-theme", themeMode);
                }
            })()
        </script>
        {{
            Vite::useHotFile(storage_path('mobile.hot'))
                ->useBuildDirectory('mobile')
                ->withEntryPoints(['resources/css/app.scss', 'resources/js/mobile.js']) 
        }}
    </head>
    <body ontouchstart class="hover-scroll">
        <div id="app"></div>
    </body>
</html>