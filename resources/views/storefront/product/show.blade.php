<x-store-main-layout>
    <main>
        <x-product.section.details :product="$product" :media="$media" />

        <x-product.section.video />

        <x-product.section.more-info :product="$product" />
        {{-- <x-product.section.image-w-details /> --}}
        <x-product.section.faqs :faqs="$faqs" />
        <livewire:more-product :current-product="$product->id" />
    </main>
</x-store-main-layout>
