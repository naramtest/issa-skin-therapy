<x-store-main-layout>
    <main class="relative">
        <x-home.section.hero-swiper />
        <x-home.section.collection :bundles="$bundles" :categories="$categories" />

        <x-home.section.best-seller-product :product="$featuredProduct" />
        <x-home.section.vedio-background />
        <x-home.section.boxes />
        <x-home.section.image-com />
        <x-home.section.testimonial />

        <x-home.section.blog />

        <x-home.section.shop />

        <x-home.section.logos />
    </main>
    {{-- <livewire:first-visit-modal /> --}}
</x-store-main-layout>
