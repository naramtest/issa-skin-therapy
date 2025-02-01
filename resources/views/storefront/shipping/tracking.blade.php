<x-store-main-layout>
    <x-slot name="title">
        <title>{{ getPageTitle(__("store.Order Tracking")) }}</title>
    </x-slot>
    <div class="padding-from-side-menu py-12">
        <livewire:tracking-component />
    </div>
</x-store-main-layout>
