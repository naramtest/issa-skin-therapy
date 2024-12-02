<x-store-main-layout>
    <main class="padding-from-side-menu pb-24 pt-10">
        <h1 class="text-[95px] font-bold">
            {{ __("dashboard.Collections") }}
        </h1>
        <section class="mt-6">
            <div class="grid grid-cols-2 gap-8">
                @foreach ($bundles as $bundle)
                    <x-shop.shop-collection :bundle="$bundle" />
                @endforeach
            </div>
        </section>
    </main>
</x-store-main-layout>
