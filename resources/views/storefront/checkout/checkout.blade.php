<x-store-main-layout>
    <livewire:checkout-component />
    @pushonce("scripts")
        {{-- TODO: move to checkout page --}}
        <script src="https://js.stripe.com/v3/"></script>
    @endpushonce
</x-store-main-layout>
