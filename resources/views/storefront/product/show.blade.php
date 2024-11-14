<x-store-main-layout>
    <main>
        <x-product.section.details :product="$product" :media="$media" />

        <x-product.section.video />

        <x-product.section.more-info />
        <x-product.section.image-w-details />
        <x-product.section.faqs :faqs="$faqs" />
        <x-product.other-products />
    </main>
</x-store-main-layout>
