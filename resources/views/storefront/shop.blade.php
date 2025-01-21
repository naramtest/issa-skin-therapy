<x-store-main-layout>
    <main class="relative">
        <x-shop.section.hero label="{{__('dashboard.Our Products')}}" />
        <section class="padding-from-side-menu py-12">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
                @foreach ($bundles as $bundle)
                    <x-shop.shop-collection :bundle="$bundle" />
                @endforeach
            </div>
            <div class="mt-8 grid grid-cols-2 gap-6 lg:grid-cols-4">
                @foreach ($products as $product)
                    <x-general.product-item-card :product="$product" />
                @endforeach
            </div>
            {!! $products->links("pagination::tailwind") !!}
        </section>
        <x-shop.shop-footer />
    </main>
</x-store-main-layout>
