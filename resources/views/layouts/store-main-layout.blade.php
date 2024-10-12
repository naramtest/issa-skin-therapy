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
            <div class="bg-darkColor">
                <nav class="content-x-padding flex gap-x-8 bg-lightColor py-5">
                    <div class="w-[20%]">
                        <img
                            class="w-[100px]"
                            src="{{ asset("storage/images/issa-logo.webp") }}"
                            alt="{{ __("store.Logo") }}"
                        />
                    </div>
                    <ul
                        class="flex w-[50%] items-center justify-center gap-x-3"
                    >
                        <li>{{ __("store.Home") }}</li>
                        <li>{{ __("store.Shop") }}</li>
                        <li>{{ __("store.About") }}</li>
                        <li>{{ __("store.Contact Us") }}</li>
                    </ul>
                </nav>
            </div>
        </header>

        {{ $slot }}

        @livewireScriptConfig
        @stack("scripts")
    </body>
</html>
