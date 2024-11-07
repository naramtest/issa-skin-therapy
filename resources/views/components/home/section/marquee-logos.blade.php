<section class="relative my-32 overflow-x-clip">
    <x-marquee
        class="relative z-10 w-[110vw] -translate-x-5 rotate-[-3deg] bg-[#69796D] py-6"
        :repeat="15"
        :speed="50"
        :gap="50"
    >
        <img
            src="{{ asset("storage/images/issa-white.webp") }}"
            class="max-h-[50px]"
            alt="ISSA Logo"
        />
    </x-marquee>

    <x-marquee
        class="w-[110vw] -translate-x-5 rotate-[3deg] bg-[#FAFAFA] py-6"
        :repeat="10"
        :speed="50"
        :gap="50"
    >
        <img
            src="{{ asset("storage/images/issa-logo.webp") }}"
            class="max-h-[50px]"
            alt="ISSA Logo"
        />
        <img
            src="{{ asset("storage/images/gray-logo.webp") }}"
            class="max-h-[50px]"
            alt="ISSA Logo"
        />
    </x-marquee>
</section>
