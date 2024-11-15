@props([
    "index",
    "title",
])

<div
    {{ $attributes->class(["border-b-[2px] border-[#e1e1e19e]"]) }}
>
    <button
        id="controlsAccordionItemOne"
        type="button"
        class="flex w-full items-center justify-between py-5"
        aria-controls="accordionItemOne"
        @click="selectActive({{ $index }})"
        :aria-expanded="isActive({{ $index }}) ? 'true' : 'false'"
    >
        <span class="text-[1.0625rem] font-medium text-[#1f2124]">
            {{ $title }}
        </span>
        <x-gmdi-add
            x-show="!isActive({{$index}})"
            class="h-6 w-6 transform transition-transform duration-300"
        />
        <x-gmdi-remove
            x-show="isActive({{$index}})"
            class="h-6 w-6 transform transition-transform duration-300"
        />
    </button>

    <div
        x-cloak
        x-show="isActive({{ $index }})"
        id="accordionItemOne"
        role="region"
        aria-labelledby="controlsAccordionItemOne"
        x-collapse.duration.800ms
    >
        <div class="no-tailwind text-pretty py-4">
            {!! $slot !!}
        </div>
    </div>
</div>
