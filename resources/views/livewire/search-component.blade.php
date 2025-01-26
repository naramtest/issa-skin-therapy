{{-- resources/views/livewire/first-visit-modal.blade.php --}}

<div @open-search.window="show = !show" x-data="{ show: false }">
    <div
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="h-screen">
            <!-- Backdrop -->
            <div
                @click="show = false"
                x-show="show"
                x-transition:enter="duration-500 ease-out"
                x-transition:enter-start="opacity-0 "
                x-transition:enter-end="opacity-100"
                x-transition:leave="duration-200 ease-in"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-60"
            ></div>

            <!-- Modal -->
            <div
                x-show="show"
                x-transition:enter="duration-500 ease-out"
                x-transition:enter-start="translate-x-full opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="duration-200 ease-in"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="translate-x-full opacity-0"
                class="fixed end-0 flex h-full w-[35%] flex-col rounded-s-[30px] bg-white p-4"
            >
                <div
                    class="relative flex w-full items-end justify-end pe-4 pt-4"
                >
                    <button
                        @click="show = false"
                        class="z-10 hidden h-12 w-12 items-center justify-center rounded-full border border-gray-400 bg-white transition-transform hover:scale-110 md:flex"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="w-full ps-6">
                    <h2 class="text-5xl font-bold">
                        {{ __("store.Search") }}
                    </h2>
                    <div class="my-4 h-[1px] bg-gray-200"></div>

                    <div class="my-10">
                        <label class="sr-only" for="Search"></label>

                        <input
                            class="mt-1 block w-full rounded-xl bg-[#f8f8f8] px-4 py-3"
                            type="text"
                            name="search"
                            id="search"
                            wire:model.live="search"
                            placeholder="Search for ..."
                        />

                        @error("search")
                            <p class="ms-1 mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    @if (count($this->products))
                        <x-search.list
                            title="{{ __('store.Products') }}"
                            :collection="$this->products"
                            route="product.show"
                            param="product"
                        />
                    @endif

                    @if (count($this->popular))
                        <x-search.list
                            title="{{__('store.POPULAR CATEGORIES')}}"
                            :collection="$this->popular"
                            route="product.category"
                        />
                    @endif

                    <div class="mt-6">
                        <h2 class="text-[11px] uppercase text-[#8c92a4]">
                            {{ __("store.Info") }}
                        </h2>
                        <div class="my-2 h-[1px] bg-gray-200"></div>
                        <ul class="mt-2 flex flex-col gap-y-2 font-semibold">
                            <li>
                                <a href="{{ route("about.index") }}">
                                    {{ __("store.about") }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route("contact.index") }}">
                                    {{ __("store.Contact") }}
                                </a>
                            </li>

                            <li>
                                <a href="{{ route("faq.index") }}">
                                    {{ __("store.FAQ s") }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
