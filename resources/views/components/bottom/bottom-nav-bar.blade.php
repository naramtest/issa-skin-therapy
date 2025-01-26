<div
    x-data="{
        sendEvent() {
            this.$dispatch('toggle-mobile-menu', { open: true })
        },
        openLanguageModal() {
            this.$dispatch('open-modal')
        },
    }"
    class="shadow-3xl fixed bottom-0 left-0 right-0 z-[111] flex items-center justify-between rounded-t-[15px] bg-white px-3 py-4 md:hidden"
>
    {{-- Home --}}
    <a href="{{ route("storefront.index") }}">
        <x-bottom.item icon="home.svg" title="{{__('store.Home')}}" />
    </a>
    <x-bottom.item
        @click="sendEvent()"
        icon="menu.svg"
        title="{{ __('store.Menu') }}"
    />

    <x-bottom.item
        @click="openLanguageModal()"
        icon="language.svg"
        title="{{ __('store.Language') }}"
    />

    <a href="{{ route("shop.index") }}">
        <x-bottom.item icon="shop.svg" title="{{ __('store.Shop') }}" />
    </a>

    <a href="{{ route("cart.index") }}">
        <x-bottom.item icon="cart.svg" title="{{ __('store.Cart') }}" />
    </a>

    <a href="{{ route("account.index") }}">
        <x-bottom.item icon="account.svg" title="{{ __('store.Account') }}" />
    </a>
</div>
