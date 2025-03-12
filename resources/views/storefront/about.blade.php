<x-store-main-layout>
    <x-slot name="seo">
        {!! seo($seo) !!}
    </x-slot>
    <x-slot name="graph">
        {!! $graph !!}
    </x-slot>
    <main class="relative">
        <x-about.hero />
        <x-home.section-container
            class="relative z-10 -translate-y-14 bg-lightColor py-14 lg:-translate-y-8"
        >
            <x-about.about-us />
            <x-about.education />

            <x-about.formulation />
            <x-about.core-values />
            <x-about.marquee />

            <x-about.our-history />
        </x-home.section-container>
    </main>
</x-store-main-layout>
