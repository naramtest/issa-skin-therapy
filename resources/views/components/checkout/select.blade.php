{{-- select.blade.php --}}

@props([
    "label",
    "required" => false,
    "field",
])

<div
    {{ $attributes->except(["wire:model", "wire:model.live"]) }}
    x-data="{
        open: false,
        search: '',
        options: [],
        selectedOption: null,
        updateOption() {
            this.options = Array.from($refs.select.options).map((option) => ({
                value: option.value,
                text: option.textContent,
                selected: option.selected,
            }))

            // Set initial selected option
            const selected = this.options.find((option) => option.selected)
            if (selected) {
                this.selectedOption = selected
            }
        },
        init() {
            this.updateOption()
            Livewire.on('locationUpdated', () => {
                this.$nextTick(() => this.updateOption())
            })
            Livewire.hook('message.processed', () => {
                this.$nextTick(() => this.updateOption())
            })
        },
        get filteredOptions() {
            return this.options.filter((option) =>
                option.text.toLowerCase().includes(this.search.toLowerCase()),
            )
        },
    }"
    @click.away="open = false"
    class="relative"
>
    <label
        for="{{ $field }}"
        class="mb-1 block text-[.9rem] font-[700] text-[#69727d]"
    >
        <span>{{ $label }}</span>
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <!-- Hidden Select Element for Wire Model Binding -->
    <select
        x-ref="select"
        class="hidden"
        name="{{ $field }}"
        @required($required)
        id="{{ $field }}"
        {{ $attributes->only(["wire:model", "wire:model.live"]) }}
    >
        {{ $slot }}
    </select>

    <!-- Custom Select UI -->
    <div class="relative">
        <button
            type="button"
            @click="open = !open"
            class="flex w-full items-center justify-between rounded-[11px] border-none bg-[#F4F4F4] px-7 py-5 text-left text-sm text-[#69727d] focus:outline-none"
        >
            <span
                x-text="selectedOption ? selectedOption.text : 'Select an option'"
            ></span>
            <svg
                class="h-4 w-4 transition-transform"
                :class="{'rotate-180': open}"
                xmlns="http://www.w3.org/2000/svg"
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

        <!-- Dropdown -->
        <div
            x-show="open"
            x-transition
            class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-lg bg-white shadow-lg"
        >
            <!-- Search Input -->
            <div class="sticky top-0 border-b bg-white p-2">
                <input
                    type="search"
                    x-model="search"
                    placeholder="Search..."
                    class="w-full rounded-lg border px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                />
            </div>

            <!-- Options List -->
            <div class="py-1">
                <template
                    x-for="option in filteredOptions"
                    :key="option.value"
                >
                    <button
                        type="button"
                        @click="selectedOption = option;
                                open = false;
                                $refs.select.value = option.value;
                                $refs.select.dispatchEvent(new Event('change'));"
                        class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
                        :class="{'bg-gray-50': selectedOption?.value === option.value}"
                        x-text="option.text"
                    ></button>
                </template>

                <!-- No Results Message -->
                <div
                    x-show="filteredOptions.length === 0"
                    class="px-4 py-2 text-sm text-gray-500"
                >
                    {{ __("store.No results found") }}
                </div>
            </div>
        </div>
    </div>

    @error($field)
        <p class="ms-1 mt-1 text-sm text-red-500">
            {{ $message }}
        </p>
    @enderror
</div>
