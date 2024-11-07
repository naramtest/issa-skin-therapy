<nav
    class="content-x-padding hidden rounded-t-[1.25rem] bg-lightColor lg:flex lg:gap-x-8"
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
            @click="open = !open"
            :title="__('store.Shop')"
        />
        <x-layout.header.home.nav-item :title="__('store.About')" />
        <x-layout.header.home.nav-item :title="__('store.Contact Us')" />
    </ul>
    <div class="flex w-[20%] items-center justify-end gap-x-5">
        <x-icons.person class="h-7 w-7" />
        <x-icons.search class="h-6 w-6" />
        <x-icons.bookmark class="h-7 w-7" />
        <x-icons.cart-icon class="h-7 w-7" />
    </div>
</nav>
