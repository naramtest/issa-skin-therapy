@props([
    "faqSection",
])

<li {{ $attributes->class(["rounded-xl border p-5"]) }}>
    <h2 class="text-[23px] font-[700]">
        {{ $faqSection->title }}
    </h2>
    <p class="mt-3 text-[15px]">
        {{ $faqSection->description }}
    </p>
    <ul
        x-data="{
            activeIndex: -1,
            selectActive(index) {
                this.activeIndex = this.activeIndex != index ? index : -1
            },
            isActive(index) {
                return this.activeIndex == index
            },
        }"
        class="mt-10"
    >
        @foreach ($faqSection->activeFaqs as $faq)
            <li class="border-t">
                <button
                    id="controlsAccordionItemOne"
                    type="button"
                    class="flex w-full items-center justify-between py-7"
                    aria-controls="accordionItemOne"
                    @click="selectActive({{ $loop->index }})"
                    :aria-expanded="isActive({{ $loop->index }}) ? 'true' : 'false'"
                >
                    <span class="text-start font-semibold">
                        {{ $faq->question }}
                    </span>

                    <x-gmdi-add
                        x-show="!isActive({{$loop->index}})"
                        class="h-6 w-6 transform transition-transform duration-300"
                    />
                    <x-gmdi-remove
                        x-show="isActive({{$loop->index}})"
                        class="h-6 w-6 transform transition-transform duration-300"
                    />
                </button>
                <div
                    x-cloak
                    x-show="isActive({{ $loop->index }})"
                    id="accordionItemOne"
                    role="region"
                    aria-labelledby="controlsAccordionItemOne"
                    x-collapse.duration.800ms
                >
                    <div class="text-pretty py-4 text-sm">
                        {{ $faq->answer }}
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</li>
