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

        <footer class="relative">
            <x-layout.footer.home.top class="relative z-[20]" />
            <div
                class="relative z-[10] h-[300px] -translate-y-10 rounded-b-[20px] bg-darkColor"
            ></div>
        </footer>

        @livewireScriptConfig
        @stack("scripts")
    </body>
</html>
