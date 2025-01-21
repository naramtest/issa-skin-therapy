@props([
    "product",
])

<x-home.section-container
    class="padding-from-side-menu relative z-10 -translate-y-10 bg-lightColor py-12"
>
    <h2 class="headline-font">{{ __("store.More Info") }}</h2>
    <div class="mt-10">
        <div
            x-data="{
                activeIndex: -1,
                selectActive(index) {
                    this.activeIndex = this.activeIndex != index ? index : -1
                },
                isActive(index) {
                    return this.activeIndex == index
                },
            }"
            class="w-full overflow-hidden lg:px-3"
        >
            <x-product.more-info-item
                :index="0"
                title="{{ __('dashboard.Quick Facts') }}"
            >
                <span class="!text-lg !font-medium">
                    {{ $product->quick_facts_label }}
                </span>
                <div class="!ps-4">
                    {!! $product->quick_facts_content !!}
                </div>
            </x-product.more-info-item>
            <x-product.more-info-item
                :index="1"
                title="{{ __('store.Details') }}"
            >
                {!! $product->details !!}
            </x-product.more-info-item>
            <x-product.more-info-item
                :index="2"
                title="{{ __('store.How to use') }}"
            >
                {!! $product->how_to_use !!}
            </x-product.more-info-item>
            <x-product.more-info-item
                :index="3"
                title="{{ __('store.Key Ingredients') }}"
            >
                {!! $product->key_ingredients !!}
            </x-product.more-info-item>
            <x-product.more-info-item
                :index="4"
                title="{{ __('store.Full ingredients') }}"
            >
                {!! $product->full_ingredients !!}
            </x-product.more-info-item>
            <x-product.more-info-item
                :index="5"
                title="{{ __('store.Caution') }}"
            >
                {!! $product->caution !!}
            </x-product.more-info-item>
            <x-product.more-info-item
                :index="6"
                title="{{ __('store.How to store') }}"
            >
                {!! $product->how_to_store !!}
            </x-product.more-info-item>
        </div>
    </div>
</x-home.section-container>
