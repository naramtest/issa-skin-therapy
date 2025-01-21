@props([
    "faq",
    "index",
])
<div class="border-b-[2px] border-[#e1e1e19e]">
    <button
        id="controlsAccordionItemOne"
        type="button"
        class="flex w-full items-center justify-between py-5"
        aria-controls="accordionItemOne"
        @click="selectActive({{ $index }})"
        :aria-expanded="isActive({{ $index }}) ? 'true' : 'false'"
    >
        <span class="text-start text-lg font-medium">
            {{ $faq->question }}
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
        <div class="text-pretty py-4 text-sm sm:text-base">
            {{ $faq->answer }}
        </div>
    </div>
</div>
