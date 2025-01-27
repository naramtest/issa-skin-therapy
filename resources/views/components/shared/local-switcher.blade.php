<x-dynamic-component :component="'general.' . $location . '-dropdown'">
    <x-slot:button>
        <x-icons.local class="h-6 w-6 pe-1" />
        {{ App::getLocale() == "en" ? "English" : "العربية" }}
        <x-icons.drop-down class="mt-1 h-3 w-3" />
    </x-slot>
    <x-slot:dropdown>
        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li class="rounded px-4 py-1 text-sm hover:bg-gray-700">
                <a
                    rel="alternate"
                    @class(["arabic" => $localeCode === "ar"])
                    hreflang="{{ $localeCode }}"
                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                >
                    <span>
                        {{ $properties["native"] }}
                    </span>
                </a>
            </li>
        @endforeach
    </x-slot>
</x-dynamic-component>
