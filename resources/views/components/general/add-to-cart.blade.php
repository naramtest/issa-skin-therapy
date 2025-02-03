@props([
    "type",
    "product",
    "outOfStock" => false,
])
<div
    @finish-loading.window="offLoading()"
    x-data="{
        quantity: 1,
        isLoading: false,
        offLoading() {
            this.isLoading = false
            this.$dispatch('toggle-disable')
        },

        addToCart() {
            this.isLoading = true
            this.$dispatch('toggle-disable')
            Livewire.dispatch('add-to-cart', {
                type: '{{ $type }}',
                id: {{ $product->id }},
                quantity: this.quantity,
            })
        },
    }"
    {{ $attributes }}
>
    {{ $slot }}
    <button @disabled($outOfStock) class="w-full" @click="addToCart()">
        {{ $button }}
    </button>
</div>
