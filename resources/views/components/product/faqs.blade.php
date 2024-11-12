<div class="mt-10 w-full overflow-hidden text-lightColor">
    <div
        x-data="{ isExpanded: false }"
        class="border-b-[1px] border-[#DBDBDB33]"
    >
        <button
            id="controlsAccordionItemOne"
            type="button"
            class="flex w-full items-center justify-between gap-4 px-4 py-6"
            aria-controls="accordionItemOne"
            @click="isExpanded = ! isExpanded"
            :class="isExpanded ? '  font-bold'  : ' font-medium'"
            :aria-expanded="isExpanded ? 'true' : 'false'"
        >
            <span class="text-[17px] font-medium">
                What browsers are supported?
            </span>

            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke-width="2"
                stroke="currentColor"
                class="size-5 shrink-0 transition"
                aria-hidden="true"
                :class="isExpanded  ?  'rotate-180'  :  ''"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                />
            </svg>
        </button>
        <div
            x-cloak
            x-show="isExpanded"
            id="accordionItemOne"
            role="region"
            aria-labelledby="controlsAccordionItemOne"
            x-collapse.duration.500ms
        >
            <div class="text-pretty p-4 text-sm sm:text-base">
                Our website is optimized for the latest versions of Chrome,
                Firefox, Safari, and Edge. Check our
                <a href="#" class="text-black underline underline-offset-2">
                    documentation
                </a>
                for additional information.
            </div>
        </div>
    </div>
</div>
