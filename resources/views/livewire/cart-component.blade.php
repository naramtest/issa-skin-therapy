<div>
    {{-- TODO:Promo code --}}
    <!-- Cart Overlay -->
    <div
        @toggle-cart.window="open = !open"
        x-data="{ open: false }"
        x-show="open"
        x-cloak
        @keydown.escape.window="open = false"
        class="relative z-[200]"
        role="dialog"
        aria-modal="true"
    >
        <!-- Background backdrop -->
        <div
            x-show="open"
            x-transition:enter="transition-opacity duration-300 ease-in-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300 ease-in-out"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-20"
            @click="open = false"
        ></div>

        <!-- Cart panel -->
        <div
            x-show="open"
            x-transition:enter="transform transition duration-500 ease-in-out"
            x-transition:enter-start="translate-x-full rtl:-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition duration-500 ease-in-out"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full rtl:-translate-x-full"
            class="fixed inset-y-0 end-0 flex max-w-full"
        >
            <div class="w-[85vw] max-w-md md:w-screen">
                <div
                    class="flex h-full flex-col rounded-s-2xl bg-white px-4 shadow-xl"
                >
                    <!-- Cart header -->
                    <div
                        class="flex items-center justify-between border-b px-4 py-6"
                    >
                        <div>
                            <h2 class="text-lg font-medium">
                                {{ __("store.Cart") }}
                            </h2>
                            <div class="py23">
                                <p class="text-sm text-darkColor">
                                    {{ __('store.Free shipping in UAE over 270 AED and                                     worldwide over $180') }}
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="text-gray-400 hover:text-gray-500"
                            @click="open = false"
                        >
                            <span class="sr-only">
                                {{ __("store.Close panel") }}
                            </span>
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Cart items -->
                    <div
                        style="scrollbar-width: none"
                        class="flex-1 overflow-y-scroll py-4"
                    >
                        @forelse ($cartItems as $item)
                            <div class="flex px-3 py-3">
                                <div
                                    class="relative h-16 w-16 flex-shrink-0 rounded-md border"
                                >
                                    <img
                                        src="{{ \App\Helpers\Media\ImageGetter::getMediaThumbnailUrl($item->getPurchasable()) }}"
                                        alt="{{ $item->getPurchasable()->name }}"
                                        class="h-full w-full object-cover object-center"
                                    />
                                    <button
                                        wire:click="removeItem('{{ $item->getId() }}')"
                                        class="absolute start-0 top-0 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#dedfea] p-[2px] text-gray-500 hover:text-gray-600 rtl:translate-x-1/2"
                                    >
                                        <span class="sr-only">
                                            {{ __("store.Remove") }}
                                        </span>
                                        <svg
                                            class="h-3 w-3"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                <div class="ms-4 flex flex-1 flex-col">
                                    <div class="flex justify-between">
                                        <h3 class="text-sm font-semibold">
                                            {{ $item->getPurchasable()->name }}
                                        </h3>
                                        {{-- TODO:add price --}}
                                        <p class="ms-4 text-sm text-[#24272d]">
                                            {{ $item->getPrice() }}
                                        </p>
                                    </div>

                                    <div
                                        class="mt-2 flex items-center justify-between text-sm text-[#24272d]"
                                    >
                                        <div
                                            class="flex items-center rounded-[6px] border"
                                        >
                                            <button
                                                wire:click="updateQuantity('{{ $item->getId() }}', 'decrement')"
                                                class="rounded-l-[6px] px-3 py-[2px] hover:bg-gray-100"
                                            >
                                                -
                                            </button>
                                            <span
                                                class="border-x px-3 py-[2px]"
                                            >
                                                {{ $item->getQuantity() }}
                                            </span>
                                            <button
                                                wire:click="updateQuantity('{{ $item->getId() }}', 'increment')"
                                                class="rounded-r-[6px] px-3 py-[2px] hover:bg-gray-100"
                                            >
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center">
                                <p class="text-gray-500">
                                    {{ __("store.Your cart is empty") }}
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Cart footer -->
                    @if (count($cartItems) > 0)
                        <div class="border-t border-gray-200 py-4">
                            <!-- Discount code -->
                            <div
                                x-data="{ discountOpen: true }"
                                class="border-b pb-3"
                            >
                                <button
                                    @click="discountOpen = !discountOpen"
                                    class="flex w-full items-center justify-between"
                                >
                                    <span
                                        class="text-base font-medium text-gray-900"
                                    >
                                        {{ __("store.Got a discount code?") }}
                                    </span>
                                    <svg
                                        class="h-5 w-5 transition-transform"
                                        :class="{ 'rotate-180': discountOpen }"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>

                                <div
                                    x-show="discountOpen"
                                    x-collapse
                                    class="mt-4 flex"
                                >
                                    <input
                                        type="text"
                                        class="min-w-0 flex-1 rounded-s-[50px] border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="{{ __("store.Enter coupon code") }}"
                                    />
                                    <button
                                        class="rounded-e-[50px] bg-[#1f1f1f] px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-[#2f2f2f]"
                                    >
                                        {{ __("store.Apply") }}
                                    </button>
                                </div>
                            </div>

                            <div
                                class="flex justify-between pt-3 text-base font-medium text-gray-900"
                            >
                                <p>{{ __("store.Subtotal") }}</p>
                                <p>{{ $subtotalString }}</p>
                            </div>
                            <div class="mt-4 flex gap-x-6">
                                <a
                                    href="{{ route("checkout.index") }}"
                                    class="flex flex-1 items-center justify-center rounded-full bg-[#1f1f1f] px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-[#2f2f2f]"
                                >
                                    <x-gmdi-shopping-cart-checkout-o
                                        class="h-5 w-5 text-lightColor"
                                    />
                                    <span class="ms-2">
                                        {{ __("store.Checkout") }}
                                    </span>
                                </a>
                                <a
                                    href="/"
                                    class="flex flex-1 items-center justify-center rounded-full bg-[#1f1f1f] px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-[#2f2f2f]"
                                >
                                    {{ __("store.View Cart") }}
                                </a>
                            </div>
                            <div
                                class="mt-4 flex justify-center text-center text-sm text-gray-500"
                            >
                                <button
                                    type="button"
                                    class="font-medium hover:text-gray-800"
                                    @click="open = false"
                                >
                                    {{ __("store.Continue Shopping") }}
                                    <x-gmdi-arrow-forward
                                        class="inline h-3 w-3 text-black rtl:rotate-180"
                                    />
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
