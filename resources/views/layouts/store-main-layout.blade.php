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

    <body class="bg-lightColor w-full antialiased">
        <header>
            <div class="content-x-padding bg-darkColor flex py-4">
                <x-layout.header.home.social />
                <div class="flex w-[50%]"></div>
            </div>
        </header>

        {{ $slot }}

        @livewireScriptConfig
        @stack("scripts")
    </body>
</html>
