@props([
    "seo",
])
<!DOCTYPE html>
<html
    lang="{{ str_replace("_", "-", app()->getLocale()) }}"
    dir="{{ app()->getLocale() == "en" ? "ltr" : "rtl" }}"
>
    <head>
        <x-google-tag />

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @if (isset($title))
            {{ $title }}
        @endif

        <link
            rel="stylesheet"
            type="text/css"
            href="{{ asset("vendor/cookie-consent/css/cookie-consent.css") }}"
        />
        <link
            rel="icon"
            type="image/png"
            href="{{ asset("favicon-96x96.png") }}"
            sizes="96x96"
        />
        <link
            rel="icon"
            type="image/svg+xml"
            href="{{ asset("favicon.svg") }}"
        />
        <link rel="shortcut icon" href="{{ asset("favicon.ico") }}" />
        <link
            rel="apple-touch-icon"
            sizes="180x180"
            href="{{ asset("apple-touch-icon.png") }}"
        />
        <link rel="manifest" href="{{ asset("site.webmanifest") }}" />
        {{ $seo ?? null }}
        {{ $graph ?? null }}
        {{ $keywords ?? null }}

        {{-- <x-general.facebook-pixel /> --}}

        @if (app()->getLocale() == "en")
            @googlefonts
        @endif

        @googlefonts("alexandria")

        <!-- Styles -->
        @stack("styles")
        @livewireStyles

        @vite(["resources/css/app.css", "resources/js/app.js"])
        @stack("header-scripts")
    </head>

    <body class="w-full bg-lightColor antialiased">
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe
                src="https://www.googletagmanager.com/ns.html?id=GTM-5KXKMSZQ"
                height="0"
                width="0"
                style="display: none; visibility: hidden"
            ></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->
        <x-layout.header-home />
        <x-layout.fixed-menu />

        {{ $slot }}

        <livewire:cart-component />
        <x-layout.footer-home />
        <x-bottom.bottom-nav-bar />
        <x-bottom.language-modal />

        <livewire:search-component />
        <livewire:alert />

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
