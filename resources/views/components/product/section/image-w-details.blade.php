<x-home.section-container class="content-x-padding flex justify-between py-12">
    <div class="w-[35%] ps-7">
        <h2 class="text-[30px] font-bold">SaliCleanse Cleanser</h2>
        <div class="no-tailwind my-6">
            <ul class="text-darkColor">
                <li>Suitable for acne prone or oily skin</li>
                <li>Helps unclog pores</li>
                <li>
                    Gently exfoliates with
                    <strong>2% salicylic acid</strong>
                </li>
            </ul>
        </div>
        <div
            class="flex gap-x-8 rounded-[10px] border-[1px] border-[#D1D5DB] px-5 py-5"
        >
            <div class="flex-1 text-sm text-darkColor">
                <span>4 interest-free payments of</span>
                <strong>AED 55.00</strong>
                <span>. No fees. Shariah-compliant.</span>
                <a class="underline" href="/">Learn more</a>
            </div>
            <img
                class="h-[30px] w-[80px]"
                src="{{ asset("storage/icons/tabby.svg") }}"
                alt="{{ __("store.Tabby") }}"
            />
        </div>
        <button
            class="mt-8 rounded-3xl bg-darkColor px-6 py-2 text-sm text-lightColor hover:bg-[#333F43]"
        >
            {{ __("store.Add to Card") }}
        </button>
    </div>
    <div class="flex w-[55%] gap-6">
        <div class="flex-1">
            <img
                class="h-[315px] w-full rounded-2xl object-cover"
                src="{{ asset("storage/test/image with text/1.webp") }}"
                alt=""
            />
            <img
                class="mt-6 h-[500px] w-full rounded-2xl object-cover"
                src="{{ asset("storage/test/image with text/3.webp") }}"
                alt=""
            />
        </div>
        <div class="flex-1">
            <img
                class="h-[500px] w-full rounded-2xl object-cover"
                src="{{ asset("storage/test/image with text/2.webp") }}"
                alt=""
            />
            <img
                class="mt-6 h-[315px] w-full rounded-2xl object-cover"
                src="{{ asset("storage/test/image with text/4.webp") }}"
                alt=""
            />
        </div>
    </div>
</x-home.section-container>
