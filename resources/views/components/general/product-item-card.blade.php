@props([
    "title",
    "img",
    "subtitle",
    "product",
])

<div
    wire:key="{{ $product->id }}"
    x-data="{ isModalOpen: false }"
    {{ $attributes->class(["!flex flex-col rounded-[15px] bg-[#F4F4F4] p-2"]) }}
>
    <div class="group relative">
        <img
            class="h-[400px] w-full rounded-[10px] object-cover"
            src="{{ $img }}"
            alt=""
        />
        <button
            @click="isModalOpen = true"
            class="absolute end-3 top-4 rounded-full border p-3 transition-colors hover:bg-white/80"
        >
            <x-gmdi-visibility-o class="h-5 w-5 text-gray-500" />
        </button>
        <button
            @click="Livewire.dispatch('toggle-cart')"
            class="absolute bottom-16 start-1/2 -translate-x-1/2 translate-y-full scale-0 rounded-[50px] bg-darkColor px-5 py-2 text-sm text-white opacity-0 transition-all duration-500 group-hover:translate-y-0 group-hover:scale-100 group-hover:opacity-100"
            href="/"
        >
            {{ __("store.Add to cart") }}
        </button>
    </div>
    <div class="px-2 pb-3 pt-5">
        <div>
            <p class="text-xs text-[#8C92A4]">{{ $subtitle }}</p>

            <div
                class="mt-3 flex items-center justify-between gap-x-2 text-darkColor"
            >
                <h3 class="flex-1 text-[17px] font-semibold">
                    {{ $title }}
                </h3>
                <p
                    @class(["-translate-y-4 text-center" => $product->isOnSale()])
                >
                    <bdi
                        @class(["text-[14px]" => ! $product->isOnSale(), "text-xs text-gray-400 line-through" => $product->isOnSale()])
                    >
                        {{ $product->money_regular_price }}
                    </bdi>
                    @if ($product->isOnSale())
                        <bdi class="block text-[14px]">
                            {{ $product->money_sale_price }}
                        </bdi>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Quick View Modal -->
    <template x-teleport="body">
        <div
            x-show="isModalOpen"
            x-transition:enter="transition duration-500 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition duration-200 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto"
            @click.self="isModalOpen = false"
        >
            <!-- Modal Backdrop -->
            <div class="fixed inset-0 bg-black/50"></div>

            <!-- Modal Content -->
            <div
                class="relative flex min-h-screen items-center justify-center p-4"
            >
                <div
                    @click.outside="isModalOpen = false"
                    class="relative w-full max-w-4xl overflow-hidden rounded-2xl bg-white px-12 py-20 shadow-xl"
                >
                    <!-- Close Button -->
                    <button
                        @click="isModalOpen = false"
                        class="absolute right-4 top-4 rounded-full border p-3 text-gray-400 hover:text-gray-600"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </button>

                    <!-- Modal Body -->
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Product Image -->
                        <div class="relative">
                            <img
                                class="h-[400px] w-full rounded-lg object-cover"
                                src="{{ $img }}"
                                alt="{{ $title }}"
                            />
                        </div>

                        <!-- Product Details -->
                        <div class="py-6">
                            <span class="text-sm text-[#8C92A4]">
                                {{ $subtitle }}
                            </span>
                            <h2 class="mt-2 text-2xl font-semibold">
                                {{ $title }}
                            </h2>
                            <p class="mt-4 text-xl font-medium">
                                {{ $product->money_regular_price }}
                            </p>

                            <div class="no-tailwind mt-6">
                                <ul class="text-darkColor">
                                    <li>
                                        Suitable for acne prone or oily skin
                                    </li>
                                    <li>Helps unclog pores</li>
                                    <li>
                                        Gently exfoliates with
                                        <strong>2% salicylic acid</strong>
                                    </li>
                                </ul>
                            </div>

                            <div
                                class="mt-8 flex items-center justify-between gap-x-6 text-sm"
                            >
                                <label for="quantity">
                                    <input
                                        class="rounded-[50px] bg-[#F4F4F4] px-2 py-2 text-center focus-visible:outline-0"
                                        type="number"
                                        name="quantity"
                                        id="quantity"
                                        value="1"
                                        min="1"
                                        max="30"
                                    />
                                </label>
                                <button
                                    class="hover:bg-darkColor/90 w-full rounded-[50px] bg-darkColor px-3 py-2 text-white transition-colors duration-300 hover:bg-[#2f2f2f]"
                                >
                                    {{ __("store.Add to cart") }}
                                </button>
                                <button
                                    class="w-full rounded-[50px] border border-black px-3 py-2 transition-colors duration-300 hover:border-transparent hover:bg-[#2f2f2f] hover:text-lightColor"
                                >
                                    {{ __("store.Check Out") }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
