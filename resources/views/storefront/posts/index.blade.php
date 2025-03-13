<x-store-main-layout>
    <x-slot name="seo">
        {!! seo($seo) !!}
    </x-slot>
    <x-slot name="graph">
        {!! $graph !!}
    </x-slot>
    <h1 class="mt-12 text-center text-[6rem] font-[800] rtl:text-4xl">
        {{ __("store.Blog") }}
    </h1>
    <livewire:post-list :categories="$categories" />
</x-store-main-layout>
