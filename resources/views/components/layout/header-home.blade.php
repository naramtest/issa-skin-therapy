<header {{ $attributes }}>
    <div class="content-x-padding flex gap-x-10 bg-darkColor py-4">
        <x-layout.header.home.social class="w-[20%] gap-x-6" />
        <x-layout.header.home.alert-swiper />
        <div class="flex w-[30%] justify-end gap-x-4">
            <x-shared.local-switcher />
            <x-shared.currency-switcher />
        </div>
    </div>
    <div
        id="nav"
        class="relative bg-darkColor"
        x-data="{
            open: false,
            sticky: false,
            scrollPosition: 0,
            navOffset: 0,
        }"
        x-init="
            navOffset = $el.offsetTop
            window.addEventListener('scroll', () => {
                scrollPosition = window.scrollY
                sticky = scrollPosition > navOffset
            })
        "
        :class="{
            'fixed top-0 left-0 w-full z-50 shadow-md': sticky,
            'relative': !sticky
        }"
    >
        <nav
            @mouseenter="open = false"
            class="content-x-padding flex gap-x-8 rounded-t-[1.25rem] bg-lightColor"
        >
            <div class="nav-padding w-[20%]">
                <img
                    class="w-[100px]"
                    src="{{ asset("storage/images/issa-logo.webp") }}"
                    alt="{{ __("store.Logo") }}"
                />
            </div>
            <ul class="flex w-[60%] items-center justify-center gap-x-5">
                <x-layout.header.home.nav-item :title="__('store.Home')" />
                <x-layout.header.home.shop-nav-item
                    @click="open = ! open"
                    :title="__('store.Shop')"
                />
                <x-layout.header.home.nav-item :title="__('store.About')" />
                <x-layout.header.home.nav-item
                    :title="__('store.Contact Us')"
                />
            </ul>

            <div class="flex w-[20%] items-center justify-end gap-x-5">
                <x-icons.person class="h-7 w-7" />
                <x-icons.search class="h-6 w-6" />
                <x-icons.bookmark class="h-7 w-7" />
                <x-icons.cart-icon class="h-7 w-7" />
            </div>
        </nav>
    </div>
</header>

<!-- Add this immediately after the header -->
<div
    x-data="{ height: 0 }"
    x-init="height = document.getElementById('nav').offsetHeight"
    :style="{ height: document.getElementById('nav').classList.contains('fixed') ? `${height}px` : '0px' }"
></div>
