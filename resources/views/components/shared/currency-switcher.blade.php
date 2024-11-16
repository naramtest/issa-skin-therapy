<x-dynamic-component :component="'shared.' . $location . '-dropdown'">
    {{-- TODO:add currency and change label and dropdown Items --}}
    <x-slot:button>
        <x-icons.currency class="h-6 w-6 pe-1" />
        <span class="line-clamp-1">United Arab Emirates dirham</span>
        <x-icons.drop-down class="mt-1 h-3 w-3" />
    </x-slot>
    <x-slot:dropdown>
        @foreach (\App\Helpers\Money\UserCurrency::$currencies as $currency)
            <li
                class="cursor-pointer rounded px-2 py-1 text-sm hover:bg-gray-700"
            >
                {{ $currency["name"] }} - {{ $currency["symbol"] }}
            </li>
        @endforeach
    </x-slot>
</x-dynamic-component>
