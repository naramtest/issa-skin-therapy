<x-home.section-container
    class="content-x-padding relative z-10 h-[600px] -translate-y-10 bg-lightColor pt-10"
>
    <div class="flex h-fit w-full gap-10">
        <div class="relative flex-1">
            <img
                class="h-full w-full rounded-[20px] object-cover"
                src="{{ asset("storage/test/A-luminate-r-1-scaled.webp") }}"
                alt=""
            />
            <div class="absolute bottom-10 w-full px-8 text-white">
                <a
                    class="rounded-[50px] bg-[#92E1D8] px-4 py-2 text-sm"
                    href="/"
                >
                    A-Luminate
                </a>
                <div class="mt-4 flex items-center text-sm">
                    <img
                        class="h-5 w-5"
                        src="{{ asset("storage/icons/calendar.svg") }}"
                        alt="{{ __("store.Calendar") }}"
                    />
                    <div class="ms-1">July 16, 2024</div>
                    <div class="mx-2 h-full w-[1px] bg-white"></div>
                    <img
                        class="h-5 w-5"
                        src="{{ asset("storage/icons/comments.svg") }}"
                        alt="{{ __("store.Comments") }}"
                    />
                    <div class="ms-1">No Comments</div>
                </div>
                <h2 class="my-8 text-4xl font-[700] leading-[44px]">
                    Understanding Melasma: Causes, Treatments, and Prevention
                </h2>
                <p>
                    Melasma, often referred to as the "mask of pregnancy," is a
                    common skin condition characterized by brown or gray-brown
                    patches ...
                </p>
                <a class="mt-8 block" href="/">Read More</a>
            </div>
        </div>
        <div class="h-fit flex-1">
            <div class="flex items-start gap-x-8">
                <div class="relative w-[40%]">
                    <img
                        class="max-h-[310px] w-full rounded-[15px] object-cover"
                        src="{{ asset("storage/test/blog2.webp") }}"
                        alt="{{ __("store.Featured Image") }}"
                    />
                    <a
                        class="absolute start-4 top-3 rounded-[50px] bg-[#d7e6be] px-4 py-2 text-xs"
                        href="/"
                    >
                        A-Clear
                    </a>
                </div>
                <article class="w-[60%]">
                    <div class="flex">
                        <img
                            class="h-5 w-5"
                            src="{{ asset("storage/icons/blog-date.svg") }}"
                            alt=""
                        />
                        <span class="ms-1 text-sm">July 16, 2024</span>
                    </div>
                    <h2 class="my-6 text-2xl font-[700] leading-[30px]">
                        Acne: Understanding, Treating, and Preventing Breakouts
                    </h2>
                    <p class="my-4 text-base leading-[25px]">
                        Acne is one of the most common skin conditions affecting
                        millions of people worldwide, regardless of age or
                        gender. While ...
                    </p>

                    <a class="my-4 underline" href="/">Read More</a>
                </article>
            </div>
            <div class="my-6 h-[1px] w-full bg-[#B9B9B999]"></div>
            <div class="flex items-start gap-x-8">
                <div class="relative w-[40%]">
                    <img
                        class="max-h-[310px] w-full rounded-[15px] object-cover"
                        src="{{ asset("storage/test/blog3.webp") }}"
                        alt="{{ __("store.Featured Image") }}"
                    />
                    <a
                        class="absolute start-4 top-3 rounded-[50px] bg-[#d7e6be] px-4 py-2 text-xs"
                        href="/"
                    >
                        Lumiguard
                    </a>
                </div>
                <article class="w-[60%]">
                    <div class="flex">
                        <img
                            class="h-5 w-5"
                            src="{{ asset("storage/icons/blog-date.svg") }}"
                            alt=""
                        />
                        <span class="ms-1 text-sm">July 16, 2024</span>
                    </div>
                    <h2 class="my-6 text-2xl font-[700] leading-[30px]">
                        The Essential Guide to Sunscreen: Protecting Your Skin
                        from the Sun
                    </h2>
                    <p class="my-4 text-base leading-[25px]">
                        Sunscreen is a non-negotiable step in any skincare
                        routine, yet it’s often overlooked or misunderstood.
                        With the rise in awareness ...
                    </p>

                    <a class="my-4 underline" href="/">Read More</a>
                </article>
            </div>
        </div>
    </div>
</x-home.section-container>
