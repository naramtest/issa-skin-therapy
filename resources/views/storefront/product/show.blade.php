<x-store-main-layout>
    <x-slot name="title">
        <title>{{ getPageTitle($product->name) }}</title>
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
            fbq('track', 'ViewContent', {
                content_ids: ['{{ $product->facebook_id }}'],
                content_type: 'product',

                currency:
                    '{{ \App\Services\Currency\CurrencyHelper::getCurrencyCode() }}',
                value: {{ \App\Services\Currency\CurrencyHelper::decimalFormatter($product->current_money_price) }},
            });
        </script>
    @endpush
</x-store-main-layout>
