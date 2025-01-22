<section class="mt-8">
    <x-marquee :repeat="15" :speed="50" :gap="50">
        <span>
            <x-icons.bigger-sign />
        </span>
        <span class="black-text-stroke text-nowrap text-[130px] font-bold">
            {{ __("store.A-CLEAR") }}
        </span>
        <span>
            <x-icons.bigger-sign />
        </span>
        <span class="black-text-stroke text-nowrap text-[130px] font-bold">
            {{ __("store.X-AGE") }}
        </span>
    </x-marquee>
    <div class="mt-6 flex flex-col items-center gap-7 pb-4">
        <img
            class="h-12 w-12"
            src="{{ asset("storage/icons/qu.svg") }}"
            alt="icon"
        />
        <h2 class="text-center text-3xl font-bold md:text-start">
            {{ __("store. A passion Turned Into A Successful Side Hustle") }}
        </h2>
        <p class="text-[17px] italic">
            - {{ __("store.Julian Bachour, Founder of ISSA") }}
        </p>
    </div>
</section>
