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
        @googlefonts
        <!-- Styles -->
        @stack("styles")
        @livewireStyles

        @vite(["resources/css/app.css", "resources/js/app.js"])
        @stack("header-scripts")
    </head>

    <body class="w-full bg-lightColor antialiased">
        <x-layout.header-home />
        <x-layout.fixed-menu />
        {{ $slot }}
        <livewire:cart-component />
        <x-layout.footer-home />

        @livewireScriptConfig
        @stack("scripts")
    </body>
</html>
