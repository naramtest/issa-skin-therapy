<x-store-main-layout>
    <x-slot name="seo">
        {!! seo()->for($bundle) !!}
    </x-slot>

    <x-slot name="graph">
        {!! $graph !!}
    </x-slot>

    <main>
        <x-product.section.details
            :type="\App\Enums\ProductType::BUNDLE->value"
            :product="$bundle"
            :media="$media"
        />

        <x-bundle.video-section :bundle="$bundle" />
        <x-bundle.how-to-use-section :bundle="$bundle" :faqs="$faqs" />
        <x-bundle.extra-tips :bundle="$bundle" />

        <x-home.section-container class="padding-from-side-menu py-12">
            <h2 class="text-3xl font-[800] leading-[48px] md:text-[2.75rem]">
                {{ __("store.Included in the collection") }}
            </h2>
            <div class="w-full text-center">
                <img
                    class="mx-auto mt-8"
                    width="250px"
                    src="{{ asset("storage/images/box-handle-.webp") }}"
                    alt="{{ __("store.Box Handel") }}"
                />
                <div
                    class="grid w-full grid-cols-1 items-center justify-between gap-3 rounded-lg bg-[#FAFAFA] px-12 py-4 md:grid-cols-2 lg:flex"
                >
                    @foreach ($bundle->products as $product)
                        <div
                            class="flex flex-col items-center justify-center gap-y-3"
                        >
                            <a href="{{ route("product.show", $product) }}">
                                {!! \App\Helpers\Media\ImageGetter::responsiveFeaturedImg($product, class: "w-[200px]") !!}
                            </a>

                            <p class="font-semibold">{{ $product->name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-home.section-container>

        <x-product.section.faqs :faqs="$faqs" />
    </main>
    @push("scripts")
        <script>
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                event: 'ViewContent',
                content_ids: ['{{ $bundle->facebook_id }}'],
                content_type: 'product',
                quantity: 1,
                description: '{{ strip_tags($bundle->description) }}',
                currency:
                    '{{ \App\Services\Currency\CurrencyHelper::getCurrencyCode() }}',
                value: {{ \App\Services\Currency\CurrencyHelper::decimalFormatter($bundle->current_money_price) }},
            });
        </script>
    @endpush
</x-store-main-layout>
