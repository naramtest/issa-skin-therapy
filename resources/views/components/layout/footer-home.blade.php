<footer {{ $attributes->class(["bg-footerColor relative"]) }}>
    {{-- TODO: add nav item and links --}}
    <x-layout.footer.home.top class="relative z-[20]" />
    <div
        class="content-x-padding relative z-[10] flex -translate-y-4 flex-col gap-8 rounded-b-[20px] bg-darkColor py-14 text-white lg:flex-row"
    >
        <div class="w-[10%]">
            <img
                class="h-[50px] w-[100px]"
                src="{{ asset("storage/images/issa-white.webp") }}"
                alt="{{ __("store.Logo") }}"
            />
        </div>

        <x-layout.footer.home.navigation />
        <div
            class="flex w-[40%] flex-col justify-between border-s border-[#ffffff21] px-10 text-white"
        >
            <div>
                <h3 class="text-[36px] font-bold leading-[43px]">
                    {{ __("store.Subscribe for the latest offers & updates") }}
                </h3>
                <div class="bg-footerColor mt-6 w-[80%] rounded-lg px-4 py-4">
                    <label class="w-full" for="email">
                        <input
                            class="w-full rounded-lg border-none bg-transparent text-sm focus-visible:outline-0 focus-visible:ring-0"
                            type="email"
                            name="email"
                            id="email"
                            placeholder="Enter Your Email ...."
                            value="{{ old("email") }}"
                        />
                    </label>
                </div>
            </div>
            <x-layout.header.home.social
                class="flex-row-reverse !justify-end gap-x-6"
            />
        </div>
    </div>
    <x-layout.footer.home.bottom />
</footer>
