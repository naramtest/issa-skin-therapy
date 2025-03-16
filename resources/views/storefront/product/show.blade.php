<x-store-main-layout>
    <x-slot name="seo">
        {!! seo()->for($product) !!}
    </x-slot>
    <x-slot name="graph">
        {!! $graph !!}
    </x-slot>
    <main>
        <x-product.section.details
            :type="\App\Enums\ProductType::PRODUCT->value"
            :product="$product"
            :media="$media"
        />

        <x-product.section.video />

        <x-product.section.more-info :product="$product" />

        {{-- <x-product.section.image-w-details /> --}}
        <x-product.section.faqs :faqs="$faqs" />
        <livewire:more-product :current-product="$product->id" />
    </main>

    @push("scripts")
        <script>
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                event: 'ViewContent',
                content_ids: ['{{ $product->facebook_id }}'],
                content_type: 'product',
                quantity: 1,
                description: '{{ strip_tags($product->description) }}',
                currency:
                    '{{ \App\Services\Currency\CurrencyHelper::getCurrencyCode() }}',
                value: {{ \App\Services\Currency\CurrencyHelper::decimalFormatter($product->current_money_price) }},
            });
        </script>
    @endpush
</x-store-main-layout>
