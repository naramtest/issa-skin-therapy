@props([
    "product",
])
<div
    x-data="{
        quantity: 1,
        addToCart() {
            Livewire.dispatch('add-to-cart', {
                product: {{ $product->id }},
                quantity: this.quantity,
            })
        },
    }"
    {{ $attributes }}
>
    {{ $slot }}
    <button class="cursor-pointer" @click="addToCart()">
        {{ $button }}
    </button>
</div>
