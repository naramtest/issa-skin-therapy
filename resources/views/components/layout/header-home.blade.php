<header {{ $attributes }}>
    <x-layout.header.announcement-bar />

    {{-- Main Navigation --}}
    <x-layout.header.menu-container>
        {{-- Desktop Navigation --}}
        <x-layout.header.desktop-nav />

        <nav
            class="relative z-[200] flex items-center justify-between rounded-t-[1.25rem] bg-white px-4 py-4 md:z-[30] lg:hidden"
        >
            <!-- Menu Button with Animation -->
            <button
                @click="mobileMenu = !mobileMenu"
                class="relative z-50 h-6 w-8"
            >
                <x-icons.menu />
            </button>

            <!-- Logo -->
            <a
                href="{{ route("storefront.index") }}"
                class="absolute left-1/2 -translate-x-1/2"
            >
                <img
                    class="h-8 w-auto"
                    src="{{ asset("storage/images/issa-logo.webp") }}"
                    alt="{{ __("store.Logo") }}"
                />
            </a>

            <!-- Mobile Icons -->
            <div class="flex items-center gap-x-4">
                <button @click="$dispatch('open-search')">
                    <x-icons.search class="h-6 w-6" />
                </button>
                <button class="relative">
                    <x-icons.cart-icon
                        @click="$dispatch('toggle-cart')"
                        class="h-6 w-6"
                    />
                    {{-- TODO: add cart count here and on the desktop --}}
                    {{-- <span --}}
                    {{-- class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-darkColor text-xs text-white" --}}
                    {{-- > --}}
                    {{-- 0 --}}
                    {{-- </span> --}}
                </button>
            </div>

            <!-- Dark Overlay -->
            <div
                x-show="mobileMenu"
                x-cloak
                class="menu-overlay fixed inset-0 z-40 bg-black bg-opacity-70"
                @click="mobileMenu = false"
            ></div>

            <!-- Mobile Menu Panel -->
            <x-layout.header.mobile-menu-panel />
        </nav>
    </x-layout.header.menu-container>
</header>
