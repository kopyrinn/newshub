<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" value="{{ csrf_token() }}"/>
        <title>{{ env('APP_NAME') }}</title>

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/build/manifest.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">

        <link rel="alternate" hreflang="ru" href="{{ url('/') }}"/>
        <link rel="alternate" hreflang="kk" href="{{ url('/kk') }}/"/>
        <link rel="alternate" hreflang="en" href="{{ url('/en') }}/"/>

        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#12121a">

        <meta property="og:type" content="website" />
        <meta property="og:title" content="NewsHub.kz - Информационный хаб">
        <meta property="og:site_name" content="Newshub.kz">
        <meta property="og:description" content="Информационный хаб NewsHub.kz  — ? это интернет-площадка для эффективного взаимодействия пресс-служб организаций со средствами массовой информации.">

        <meta name="author" content="NewsHub.kz">
        <link rel="image_src" href="{{ url('android-chrome-512x512.png') }}" />

        <meta property="og:image" content="{{ url('android-chrome-512x512.png') }}">
        <meta property="og:image:width" content="512px" />
        <meta property="og:image:height" content="512px" />
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:image:type" content="image/png" />

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <script>
            (function() {
                var defaultThemeMode = "system";
                var themeMode;
                if (document.documentElement) {
                    if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                        themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                    } else {
                        if (localStorage.getItem("data-bs-theme") !== null) {
                            themeMode = localStorage.getItem("data-bs-theme");
                        } else {
                            themeMode = defaultThemeMode;
                        }
                    }
                    if (themeMode === "system") {
                        themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                    }
                    document.documentElement.setAttribute("data-bs-theme", themeMode);
                }
            })()
        </script>
    </head>
    <body id="kt_app_body" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" class="app-default">
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root"></div>
    </body>
</html>