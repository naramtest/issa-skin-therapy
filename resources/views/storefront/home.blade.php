<x-store-main-layout>
    <x-slot name="seo">
        {!! seo($seo) !!}
    </x-slot>
    <x-slot name="graph">
        {!! $graph !!}
    </x-slot>
    <main class="relative">
        <x-home.section.hero-swiper />
        <x-home.section.home-collection
            :bundles="$bundles"
            :categories="$categories"
        />

        <x-home.section.best-seller-product :product="$featuredProduct" />
        <x-home.section.vedio-background />
        <x-home.section.boxes :bundles="$bundles" />
        <x-home.section.image-com />
        <x-home.section.testimonial />

        <x-home.section.blog :posts="$posts" />

        <x-home.section.shop />

        <x-home.section.logos />
    </main>
    <livewire:first-visit-modal />

    @if (App::isProduction())
    @endif
</x-store-main-layout>
