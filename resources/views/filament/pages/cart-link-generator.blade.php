<x-filament-panels::page>
    <x-filament::section>
        <form wire:submit="generateUrl" class="space-y-6">
            {{ $this->form }}

            <div class="flex items-center gap-4">
                <x-filament::button type="submit">
                    Generate Link
                </x-filament::button>
            </div>
        </form>

        @if ($generatedUrl)
            <div class="mt-6">
                <x-filament::section>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-base font-medium">Generated URL</h3>
                        </div>

                        <div
                            x-data="{
                                copy() {
                                    console.log($wire.generatedUrl)
                                    Livewire.dispatch('copy-to-clipboard')
                                    navigator.clipboard.writeText('{{ $generatedUrl }}')
                                },
                            }"
                            class="flex items-center gap-2"
                        >
                            <x-filament::input.wrapper class="w-1/2">
                                <x-filament::input
                                    type="text"
                                    value="{{ $generatedUrl }}"
                                />
                            </x-filament::input.wrapper>

                            <x-filament::button
                                color="gray"
                                x-data
                                @click="copy()"
                            >
                                Copy
                            </x-filament::button>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <x-filament::icon
                                icon="heroicon-m-information-circle"
                                class="h-5 w-5 text-gray-400"
                            />
                            <span class="text-gray-500">
                                When a customer clicks this link, their cart
                                will be pre-filled with the selected items.
                            </span>
                        </div>
                    </div>
                </x-filament::section>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
