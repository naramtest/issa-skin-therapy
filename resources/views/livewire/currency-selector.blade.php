<x-dynamic-component :component="'shared.' . $location . '-dropdown'">
    <x-slot:button>
        <x-icons.currency class="h-6 w-6 pe-1" />
        <span class="line-clamp-1">{{ $selectedCurrency }}</span>
        <x-icons.drop-down class="mt-1 h-3 w-3" />
    </x-slot>
    <x-slot:dropdown>
        @foreach ($currencies as $currency)
            <li
                wire:click="selectCurrency('{{ $currency["code"] }}')"
                class="cursor-pointer rounded px-2 py-1 text-sm hover:bg-gray-700"
            >
                {{ $currency["name"] }} - {{ $currency["symbol"] }}
            </li>
        @endforeach
    </x-slot>
</x-dynamic-component>
