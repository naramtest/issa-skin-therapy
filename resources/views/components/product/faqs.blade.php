<div
    x-data="{
        activeIndex: -1,
        selectActive(index) {
            this.activeIndex = this.activeIndex != index ? index : -1
        },
        isActive(index) {
            return this.activeIndex == index
        },
    }"
    class="mt-10 w-full overflow-hidden px-3 text-lightColor"
>
    @php
        $faqs = [
            [
                "question" => "What is Flowbite?",
                "answer" => "Flowbite is an open-source library of interactive components built on top of Tailwind CSS including buttons, dropdowns, modals, navbars, and more.",
            ],
            [
                "question" => "Is there a Figma file available?",
                "answer" => "Yes, there is a Figma file available that includes all components and variations.",
            ],
            [
                "question" => "What are the differences between Flowbite and Tailwind UI?",
                "answer" => "While both are built on Tailwind CSS, Flowbite is open-source and includes more components. Tailwind UI is a premium product with more polished design.",
            ],
        ];
    @endphp

    @foreach ($faqs as $faq)
        <div class="border-b-[2px] border-[#e1e1e19e]">
            <button
                id="controlsAccordionItemOne"
                type="button"
                class="flex w-full items-center justify-between py-5"
                aria-controls="accordionItemOne"
                @click="selectActive({{ $loop->index }})"
                :aria-expanded="isActive({{ $loop->index }}) ? 'true' : 'false'"
            >
                <span class="text-lg font-medium">
                    What browsers are supported?
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
                <div class="text-pretty py-4 text-sm sm:text-base">
                    Our website is optimized for the latest versions of Chrome,
                    Firefox, Safari, and Edge. Check our
                    <a href="#" class="text-black underline underline-offset-2">
                        documentation
                    </a>
                    for additional information.
                </div>
            </div>
        </div>
    @endforeach
</div>
