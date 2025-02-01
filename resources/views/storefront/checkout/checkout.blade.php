<x-store-main-layout>
    <x-slot name="title">
        <title>{{ getPageTitle(__("store.Checkout")) }}</title>
    </x-slot>
    <livewire:checkout-component />
    @pushonce("scripts")
        <script src="https://js.stripe.com/v3/"></script>
    @endpushonce
</x-store-main-layout>
