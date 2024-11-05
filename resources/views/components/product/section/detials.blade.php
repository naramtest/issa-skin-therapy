<x-home.section-container
    class="padding-from-side-menu flex justify-between py-12"
>
    <x-product.product-gallery />
    <div class="flex w-[36%] flex-col">
        <p class="text-sm font-[300] leading-[20px] text-darkColor">
            Patented P.E.T.® Technology
        </p>
        <h2 class="mb-3 mt-3 text-4xl font-bold">SaliCleanse Cleanser</h2>
        <p class="mb-3 text-lg">€55.22</p>
        <div class="flex">
            <span aria-hidden="true" class="rating-star hidden lg:block"></span>
            <p class="ms-2 font-[300] text-darkColor">(15 customer reviews)</p>
        </div>
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
        <div class="my-4 flex items-center">
            <label for="quantity">
                <input
                    class="rounded-[50px] border-[1px] border-[#D1D5DB] px-2 py-2 text-center focus-visible:outline-0"
                    type="number"
                    name="quantity"
                    id="quantity"
                    value="1"
                    min="1"
                    max="30"
                />
            </label>
            <button
                class="ms-4 flex-1 rounded-3xl bg-darkColor py-2 text-lightColor hover:bg-[#333F43]"
            >
                {{ __("store.Add to Card") }}
            </button>
        </div>
        <button
            class="rounded-3xl border border-darkColor py-2 transition-colors duration-300 hover:bg-darkColor hover:text-lightColor"
        >
            {{ __("store.Check Out") }}
        </button>
        <div class="mt-6 flex justify-between px-2">
            <div class="flex gap-x-2">
                <span>{{ __("store.Social:") }}</span>
                <x-layout.header.home.social
                    width="w-5"
                    height="h-5"
                    color="text-black"
                    class="gap-x-2"
                />
            </div>
            <div class="flex gap-x-2">
                <x-icons.qustion-mark />
                <span>{{ __("store.Need help? Contact us") }}</span>
            </div>
        </div>

        <a
            href="/"
            class="mt-6 flex items-center justify-between border-t-[1px] border-[#A5BBC4] pt-6"
        >
            <p class="text-sm font-semibold">
                {{ __("store.View full details") }}
            </p>
            <x-icons.arrow-right class="h-5 w-5" />
        </a>
    </div>
</x-home.section-container>
