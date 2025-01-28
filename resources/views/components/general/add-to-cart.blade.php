@props([
    "type",
    "product",
])
<div
    x-data="{
        quantity: 1,
        addToCart() {
            Livewire.dispatch('add-to-cart', {
                type: '{{ $type }}',
                id: {{ $product->id }},
                quantity: this.quantity,
            })
            this.$dispatch('toggle-cart')
        },
    }"
    {{ $attributes }}
>
    {{ $slot }}
    <button class="w-full cursor-pointer" @click="addToCart()">
        {{ $button }}
    </button>
</div>
