@props([
    "seo",
])
<!DOCTYPE html>
<html
    lang="{{ str_replace("_", "-", app()->getLocale()) }} "
    dir="{{ app()->getLocale() == "en" ? "ltr" : "rtl" }}"
>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ $title ?? config("app.name") }}</title>

        {{ $seo ?? null }}
        {{ $graph ?? null }}
        {{ $keywords ?? null }}

        <!-- Styles -->
        @stack("styles")
        @livewireStyles

        @vite(["resources/css/app.css", "resources/js/app.js"])
        @stack("header-scripts")
    </head>

    <body class="w-full bg-lightColor antialiased">
        <header>
            <div class="content-x-padding flex gap-x-10 bg-darkColor py-4">
                <x-layout.header.home.social />
                <x-layout.header.home.alert-swiper />
                <div class="flex w-[30%] justify-end gap-x-4">
                    <x-shared.local-switcher />
                    <x-shared.currency-switcher />
                </div>
            </div>
        </header>

        {{ $slot }}

        @livewireScriptConfig
        @stack("scripts")
    </body>
</html>
