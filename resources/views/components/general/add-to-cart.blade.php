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
    <div class="cursor-pointer" @click="addToCart()">
        {{ $button }}
    </div>
</div>
