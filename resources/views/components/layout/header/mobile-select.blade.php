@props([
    "options" => [],
    "selected" => null,
    "name" => "",
    "placeholder" => "Select an option",
])

<div
    x-data="{
        open: false,
        placeholder: @js($placeholder),
        selectedOption: @js($selected),
        options: @js($options),
        closeSelect() {
            this.open = false
            document.body.style.overflow = ''
        },
        openSelect() {
            this.open = true
            document.body.style.overflow = 'hidden'
        },
    }"
    class="relative"
    @click.away="closeSelect()"
>
    <button
        @click="openSelect()"
        type="button"
        class="w-full appearance-none rounded-lg bg-white px-4 py-2.5 pr-8 text-left text-sm"
    >
        <span x-text="selectedOption?.label ?? placeholder"></span>
        <x-icons.select-arrow-down />
    </button>

    <!-- Overlay -->
    <div
        @click="closeSelect()"
        x-cloak
        class="fixed inset-0 z-50 rounded-t-[20px] bg-black bg-opacity-50"
        x-transition:enter="transition duration-300 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-300 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-show="open"
    >
        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition duration-500 ease-in-out"
            x-transition:enter-start="translate-y-full opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition duration-300 ease-in"
            x-transition:leave-start="opacity-110 translate-y-0"
            x-transition:leave-end="translate-y-full opacity-0"
            class="z-60 fixed inset-x-0 bottom-0 rounded-t-[20px] bg-white"
            @click.away="closeSelect()"
        >
            <div class="relative px-4 pb-6 pt-4">
                <!-- Drag Handle -->
                <div
                    class="absolute left-1/2 top-2 h-1 w-12 -translate-x-1/2 rounded-full bg-gray-300"
                ></div>

                <!-- Title -->
                <div class="mb-4 mt-4 text-center">
                    <h3 class="text-lg font-medium">{{ $placeholder }}</h3>
                </div>

                <!-- Options List -->
                <div class="max-h-[400px] overflow-y-auto">
                    <div class="space-y-2">
                        <template
                            x-for="option in options"
                            :key="option.value"
                        >
                            <button
                                @click="selectedOption = option; closeSelect()"
                                class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-left transition-colors hover:bg-gray-50"
                                :class="{'bg-gray-50': selectedOption?.value === option.value}"
                            >
                                <span
                                    x-text="option.label"
                                    class="text-base"
                                ></span>
                                <svg
                                    x-show="selectedOption?.value === option.value"
                                    class="h-5 w-5 text-black"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Options Panel -->
</div>
