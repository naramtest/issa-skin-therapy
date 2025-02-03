@props([
    "type",
    "product",
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
    <button class="w-full cursor-pointer" @click="addToCart()">
        {{ $button }}
    </button>
</div>
