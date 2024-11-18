<x-dynamic-component :component="'general.' . $location . '-dropdown'">
    {{-- TODO:add locale package and change label and dropdown Items --}}
    <x-slot:button>
        <x-icons.local class="h-6 w-6 pe-1" />
        English
        <x-icons.drop-down class="mt-1 h-3 w-3" />
    </x-slot>
    <x-slot:dropdown>
        <li class="cursor-pointer rounded-md px-2 py-1 hover:bg-gray-100">
            First Menu
        </li>
    </x-slot>
</x-dynamic-component>
