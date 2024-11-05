<x-store-main-layout>
    <main class="relative">
        <x-shop.section.hero />
        <section class="content-x-padding py-12">
            <div class="grid grid-cols-4 gap-4">
                <x-shop.shop-collection
                    title="X- Age Collection"
                    subtitle="For Anti-Wrinkles and Anti-Aging"
                    img="{{asset('storage/test/collection1.webp')}}"
                />
                <x-shop.shop-collection
                    title="X- Age Collection"
                    subtitle="For Anti-Wrinkles and Anti-Aging"
                    img="{{asset('storage/test/collection2.webp')}}"
                />
                <x-shop.shop-collection
                    title="X- Age Collection"
                    subtitle="For Anti-Wrinkles and Anti-Aging"
                    img="{{asset('storage/test/collection3.webp')}}"
                />
                <x-shop.shop-collection
                    title="X- Age Collection"
                    subtitle="For Anti-Wrinkles and Anti-Aging"
                    img="{{asset('storage/test/collection4.webp')}}"
                />
            </div>
        </section>
    </main>
</x-store-main-layout>
