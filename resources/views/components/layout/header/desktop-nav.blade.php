<div class="relative z-[30] bg-darkColor">
    <nav
        class="content-x-padding hidden rounded-t-[1.25rem] bg-lightColor lg:flex lg:gap-x-8"
    >
        <a href="{{ route("storefront.index") }}" class="nav-padding w-[20%]">
            <img
                class="w-[100px]"
                src="{{ asset("storage/images/issa-logo.webp") }}"
                alt="{{ __("store.Logo") }}"
            />
        </a>
        <ul class="flex w-[60%] items-center justify-center gap-x-5">
            @foreach (\App\Services\Nav::headerPages() as $page)
                @if ($page["name"] == "Shop")
                    <x-layout.header.home.shop-nav-item
                        @click="open = !open"
                        :title="__('store.Shop')"
                    />
                @else
                    <x-layout.header.home.nav-item
                        :title="$page['title']"
                        url="{{route($page['route'])}}"
                    />
                @endif
            @endforeach
        </ul>
        <div class="flex w-[20%] items-center justify-end gap-x-5">
            <a
                href="{{ Auth::check() ? route("account.index") : route("login") }}"
            >
                <x-icons.person class="h-7 w-7" />
            </a>
            <div
                x-data="{
                    openSearch() {
                        this.$dispatch('open-search')
                    },
                }"
                @click="openSearch()"
            >
                <x-icons.search
                    class="h-6 w-6 cursor-pointer transition-transform duration-300 hover:scale-110"
                />
            </div>

            {{-- <x-icons.bookmark class="h-7 w-7" /> --}}
            <x-icons.cart-icon
                x-data
                @click="$dispatch('toggle-cart')"
                class="h-7 w-7 cursor-pointer transition-transform duration-300 hover:scale-110"
            />
        </div>
    </nav>
</div>
{{-- Mega Menu --}}
<x-mega-menu-component />
