<header {{ $attributes }}>
    <x-layout.header.announce-bar />
    {{-- Main Navigation --}}
    <x-layout.header.menu-container>
        {{-- Desktop Navigation --}}
        <x-layout.header.main-nav-desktop />

        <nav
            class="flex items-center justify-between rounded-t-[1.25rem] bg-white px-4 py-4 lg:hidden"
        >
            <!-- Menu Button with Animation -->
            <button
                @click="mobileMenu = !mobileMenu"
                class="relative z-50 h-6 w-8"
            >
                <x-icons.menu-icon />
            </button>

            <!-- Logo -->
            <div class="absolute left-1/2 -translate-x-1/2">
                <img
                    class="h-8 w-auto"
                    src="{{ asset("storage/images/issa-logo.webp") }}"
                    alt="{{ __("store.Logo") }}"
                />
            </div>

            <!-- Mobile Icons -->
            <div class="flex items-center gap-x-4">
                <button @click="searchOpen = !searchOpen">
                    <x-icons.search class="h-6 w-6" />
                </button>
                <button class="relative">
                    <x-icons.cart-icon class="h-6 w-6" />
                    <span
                        class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-darkColor text-xs text-white"
                    >
                        0
                    </span>
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
