<div
    {{ $attributes->class(["content-x-padding relative z-[50] flex justify-between gap-x-10 bg-darkColor"]) }}
>
    <x-layout.header.home.social class="hidden w-[20%] gap-x-6 md:flex" />
    <x-layout.header.home.alert-swiper />
    <div class="hidden items-center justify-end gap-x-2 md:flex lg:w-[20%]">
        <x-shared.local-switcher location="top" />
        <livewire:currency-selector location="top" />
    </div>
</div>
