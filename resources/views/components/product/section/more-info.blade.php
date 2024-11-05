<x-home.section-container
    class="padding-from-side-menu relative z-10 -translate-y-10 bg-lightColor py-12"
>
    <h2 class="headline-font">More Info</h2>
    <div class="mt-10">
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

        <div class="w-full gap-y-4">
            @foreach ($faqs as $faq)
                <div
                    x-data="{ open: false }"
                    class="border-b border-gray-200 bg-white"
                >
                    <button
                        @click="open = !open"
                        class="flex w-full items-center justify-between p-4"
                    >
                        <span class="text-lg font-medium">
                            {{ $faq["question"] }}
                        </span>
                        <x-gmdi-add
                            x-transition=""
                            x-show="!open"
                            class="h-5 w-5 transform transition-transform duration-300"
                        />
                        <x-gmdi-close
                            x-show="open"
                            class="h-5 w-5 transform transition-transform duration-300"
                        />
                    </button>

                    <div
                        x-show="open"
                        x-collapse.duration.500ms
                        class="p-4 pt-0"
                    >
                        <p class="text-gray-600">
                            {{ $faq["answer"] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-home.section-container>
