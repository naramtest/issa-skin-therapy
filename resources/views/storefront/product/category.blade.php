<x-store-main-layout>
    <main class="relative">
        <x-shop.section.hero label="{{$collectionType->name}}" />
        <section class="padding-from-side-menu py-12">
            <div
                class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4"
            >
                @foreach ($products as $product)
                    <x-general.product-item-card :product="$product" />
                @endforeach
            </div>
        </section>
        <x-shop.shop-footer />
    </main>
</x-store-main-layout>
