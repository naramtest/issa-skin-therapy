{{-- TODO: Coupon --}}
<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-10 flex items-center justify-between">
        <h1 class="text-4xl font-bold md:text-[5rem] rtl:md:text-4xl">
            {{ __("store.Your Cart") }}
        </h1>
        <a href="{{ route("shop.index") }}" class="hidden md:block">
            <x-general.button-black-animation class="!py-3 px-6">
                <div class="relative z-10 flex items-center">
                    <x-gmdi-shopping-bag-o class="mb-[2px] h-5 w-5" />
                    <span class="ms-1">
                        {{ __("store.Continue shopping") }}
                    </span>
                </div>
            </x-general.button-black-animation>
        </a>
    </div>

    @if (count($cartItems) > 0)
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Cart Items Section -->
            <div class="lg:col-span-2">
                <div
                    class="mb-4 grid grid-cols-4 border-b pb-3 text-sm font-medium"
                >
                    <div class="col-span-2">{{ __("dashboard.Product") }}</div>
                    <div class="text-center">
                        {{ __("dashboard.Quantity") }}
                    </div>
                    <div class="text-right">{{ __("store.Subtotal") }}</div>
                </div>

                @foreach ($cartItems as $item)
                    <div
                        class="grid grid-cols-2 items-center gap-4 border-b py-4 md:grid-cols-4"
                    >
                        <!-- Product Info -->
                        <div class="col-span-2 flex items-center gap-4">
                            <button
                                wire:click="removeItem('{{ $item->getId() }}')"
                                class="text-gray-400 hover:text-gray-600"
                            >
                                <x-heroicon-o-x-circle class="h-5 w-5" />
                            </button>
                            <img
                                src="{{ \App\Helpers\Media\ImageGetter::getMediaThumbnailUrl($item->getPurchasable()) }}"
                                class="h-16 w-16 rounded object-cover"
                                alt="{{ $item->getPurchasable()->name }}"
                            />
                            <div>
                                <h3 class="font-medium">
                                    {{ $item->getPurchasable()->name }}
                                </h3>
                                <x-price
                                    class="text-sm text-gray-500"
                                    :money="$item->getPrice()"
                                />
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="flex justify-center">
                            <div class="flex items-center rounded-lg border">
                                <button
                                    wire:click="updateQuantity('{{ $item->getId() }}', 'decrement')"
                                    class="px-3 py-1 hover:bg-gray-100"
                                >
                                    -
                                </button>
                                <span class="border-x px-3 py-1">
                                    {{ $item->getQuantity() }}
                                </span>
                                <button
                                    wire:click="updateQuantity('{{ $item->getId() }}', 'increment')"
                                    class="px-3 py-1 hover:bg-gray-100"
                                >
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Subtotal -->
                        <x-price
                            class="text-right font-medium"
                            :money="$item->getSubtotal()"
                        />
                    </div>
                @endforeach

                <div class="mt-6">
                    <div class="flex gap-2">
                        <input
                            type="text"
                            wire:model="coupon_code"
                            class="flex-1 rounded-xl bg-[#f9fafa] px-4 py-2"
                            placeholder="Coupon code"
                        />
                        <button wire:click="applyCoupon()" class="w-[30%]">
                            <x-general.button-black-animation class="!py-3">
                                <span class="relative z-10 inline-block">
                                    {{ __("store.Apply") }}
                                </span>
                            </x-general.button-black-animation>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="rounded-lg bg-[#f9fafa] p-6">
                    <h2 class="mb-4 text-lg font-medium">
                        {{ __("store.Cart Totals") }}
                    </h2>

                    <!-- Subtotal -->
                    <div class="mb-4 flex justify-between">
                        <span>{{ __("store.Subtotal") }}</span>
                        <x-price
                            class="font-medium"
                            :money="$this->subtotal"
                        />
                    </div>

                    @if ($this->discount)
                        <div class="mb-4 flex justify-between">
                            <div class="flex items-center">
                                <p class="text-gray-600">
                                    {{ __("store.Coupon") }}
                                </p>
                                <p class="ms-2 text-green-600">
                                    {{ $coupon_code }}
                                </p>
                                <x-gmdi-close
                                    wire:click="removeCoupon()"
                                    class="h-4 w-4 cursor-pointer text-red-600"
                                />
                            </div>
                            <x-price
                                class="font-medium"
                                :money="$this->discount"
                            />
                        </div>
                    @endif

                    <!-- Shipping Options TODO: shipping -->
                    {{-- <div class="mb-4"> --}}
                    {{-- <h3 class="mb-2 font-medium">Shipping</h3> --}}
                    {{-- <div class="space-y-2"> --}}
                    {{-- <label class="flex items-center"> --}}
                    {{-- <input --}}
                    {{-- type="radio" --}}
                    {{-- name="shipping" --}}
                    {{-- value="free" --}}
                    {{-- checked --}}
                    {{-- /> --}}
                    {{-- <span class="ml-2">Free shipping</span> --}}
                    {{-- </label> --}}
                    {{-- <label class="flex items-center"> --}}
                    {{-- <input --}}
                    {{-- type="radio" --}}
                    {{-- name="shipping" --}}
                    {{-- value="express" --}}
                    {{-- /> --}}
                    {{-- <span class="ml-2"> --}}
                    {{-- Domestic Express - 2 business days: د.إ53.86 --}}
                    {{-- </span> --}}
                    {{-- </label> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}

                    <!-- Total -->
                    <div class="mb-6 flex justify-between border-t pt-4">
                        <span class="font-medium">
                            {{ __("store.Total") }}
                        </span>
                        <x-price class="font-medium" :money="$this->total" />
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <a
                            href="{{ route("checkout.index") }}"
                            class="block w-full"
                        >
                            <x-general.button-black-animation>
                                <span class="relative z-10 inline-block">
                                    {{ __("store.Proceed to Checkout") }}
                                </span>
                            </x-general.button-black-animation>
                        </a>
                    </div>
                </div>
                <div class="mt-8 flex flex-col items-center justify-center">
                    <p class="text-xl font-semibold">{{ __("store.Or") }}</p>
                    <a
                        href="{{ route("shop.index") }}"
                        class="mt-4 block md:hidden"
                    >
                        <x-general.button-black-animation class="!py-3 px-6">
                            <div class="relative z-10 flex items-center">
                                <x-gmdi-shopping-bag-o
                                    class="mb-[2px] h-5 w-5"
                                />
                                <span class="ms-1">
                                    {{ __("store.Continue shopping") }}
                                </span>
                            </div>
                        </x-general.button-black-animation>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="py-12 text-center">
            <h2 class="mb-4 text-2xl font-medium">
                {{ __("store.Your cart is empty") }}
            </h2>
        </div>
    @endif
</div>
