@props([
    "seo",
])
<!DOCTYPE html>
<html
    lang="{{ str_replace("_", "-", app()->getLocale()) }}"
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

        @if (app()->getLocale() == "en")
            @googlefonts
        @else
            @googlefonts("alexandria")
        @endif
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
        <x-bottom.bottom-nav-bar />
        <x-bottom.language-modal />

        <!-- Custom Cursor -->
        <div
            id="custom-cursor"
            class="pointer-events-none fixed left-0 top-0 z-[9999] hidden h-8 w-8 rounded-full bg-white"
            style="transform: translate(-50%, -50%)"
        >
            <div class="flex h-full w-full items-center justify-center">
                <x-gmdi-close class="h-3 w-3 text-gray-600" />
            </div>
        </div>
        @livewireScriptConfig
        @stack("scripts")
    </body>
</html>
