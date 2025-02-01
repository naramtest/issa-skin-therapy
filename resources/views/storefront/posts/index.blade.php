<x-store-main-layout>
    <x-slot name="title">
        <title>{{ getPageTitle(__("store.Blog")) }}</title>
    </x-slot>
    <h1 class="mt-12 text-center text-[6rem] font-[800] rtl:text-4xl">
        {{ __("store.Blog") }}
    </h1>
    <livewire:post-list :categories="$categories" />
</x-store-main-layout>
