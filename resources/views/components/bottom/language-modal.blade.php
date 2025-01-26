{{-- resources/views/livewire/first-visit-modal.blade.php --}}

<div @open-modal.window="show = !show" x-data="{ show: false }">
    <div
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="flex min-h-screen items-center justify-center p-4">
            <!-- Backdrop -->
            <div
                @click="show = false"
                x-show="show"
                x-transition:enter="duration-500 ease-out"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="duration-200 ease-in"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-30"
            ></div>

            <!-- Modal -->
            <div
                x-show="show"
                x-transition:enter="duration-500 ease-out"
                x-transition:enter-start="scale-95 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="duration-300 ease-in"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-95 opacity-0"
                class="relative w-full max-w-md rounded-lg bg-white p-8"
            >
                <h2 class="mb-6 text-center text-2xl font-bold" dir="auto">
                    {{ __("Choose the language") }} اختر اللغة
                </h2>

                <ul class="space-y-4">
                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li class="">
                            <a
                                rel="alternate"
                                hreflang="{{ $localeCode }}"
                                href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                            >
                                <button
                                    class="flex w-full items-center justify-between rounded-lg border p-4 hover:bg-gray-50"
                                >
                                    <span class="text-lg">
                                        {{ $properties["native"] }}
                                    </span>
                                    <svg
                                        class="h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
