<x-store-main-layout>
    <main class="padding-from-side-menu pb-24 pt-10">
        <h1 class="text-4xl font-bold lg:text-[95px] rtl:text-[60px]">
            {{ __("dashboard.Collections") }}
        </h1>
        <section class="mt-6 lg:mt-20">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                @foreach ($bundles as $bundle)
                    <x-shop.shop-collection :bundle="$bundle" />
                @endforeach
            </div>
        </section>
    </main>
</x-store-main-layout>
