<x-shared.dropdown>
    {{-- TODO:add currency and change label and dropdown Items --}}
    <x-slot:button>
        <x-icons.currency class="h-6 w-6 pe-1" />
        <span class="line-clamp-1">United Arab Emirates dirham</span>
        <x-icons.drop-down class="mt-1 h-3 w-3" />
    </x-slot>
    <x-slot:dropdown>
        <li class="cursor-pointer rounded-md px-2 py-1 hover:bg-gray-100">
            First Menu
        </li>
    </x-slot>
</x-shared.dropdown>
