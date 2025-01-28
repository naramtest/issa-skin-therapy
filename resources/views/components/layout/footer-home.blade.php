<footer
    {{ $attributes->class(["relative bg-footerColor pb-[80px] md:pb-0"]) }}
>
    <x-layout.footer.home.top class="relative z-[20]" />
    <div
        class="content-x-padding relative z-[10] flex -translate-y-4 flex-col items-center gap-8 rounded-b-[20px] bg-darkColor py-14 text-white lg:flex-row lg:items-start"
    >
        <div class="lg:w-[10%]">
            <img
                class="h-[50px] w-[100px]"
                src="{{ asset("storage/images/issa-white.webp") }}"
                alt="{{ __("store.Logo") }}"
            />
        </div>

        <x-layout.footer.home.navigation />
        <div
            class="flex w-full flex-col justify-between border-[#ffffff21] text-white lg:w-[40%] lg:border-s lg:px-10"
        >
            <div>
                <h3
                    class="text-center text-2xl font-bold lg:text-start lg:text-[36px] lg:leading-[43px] rtl:lg:text-2xl rtl:lg:leading-[45px]"
                >
                    {{ __("store.Subscribe for the latest offers & updates") }}
                </h3>
                <div
                    class="mt-4 rounded-lg bg-footerColor px-4 py-4 lg:mt-6 lg:w-[80%]"
                >
                    <label class="w-full" for="email">
                        <input
                            class="w-full rounded-lg border-none bg-transparent text-sm focus-visible:outline-0 focus-visible:ring-0"
                            type="email"
                            name="email"
                            id="email"
                            placeholder="{{ __("store.footer-email") }} ...."
                            value="{{ old("email") }}"
                        />
                    </label>
                </div>
            </div>
            <x-layout.header.home.social
                class="mt-6 !justify-center gap-x-6 lg:flex-row-reverse lg:!justify-end"
            />
        </div>
    </div>
    <x-layout.footer.home.bottom />
</footer>
