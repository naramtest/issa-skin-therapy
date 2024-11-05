<x-home.section-container class="content-x-padding pt-12">
    <div class="flex h-fit w-full overflow-hidden rounded-t-3xl">
        <div class="h-auto w-[70%] bg-darkColor px-8 py-12">
            <div class="flex items-center justify-between">
                <h2 class="text-6xl font-[800] text-lightColor">FAQs</h2>
                <div
                    class="rounded-xl bg-[#2D2D2D] px-6 py-4 font-light text-[#8C92A4]"
                >
                    <p>
                        Please read our
                        <a class="text-lightColor underline" href="/">FAQs</a>
                        page to find out more.
                    </p>
                </div>
            </div>
            <x-product.faqs />
        </div>
        <div class="h-auto w-[30%] bg-[#2D2D2D] px-8 py-12 text-lightColor">
            <div class="flex items-center justify-between gap-x-4">
                <h2 class="text-2xl font-[600]">
                    {{ __("store.Didnt find you answer?") }}
                </h2>
                <img
                    class="w-[30%]"
                    src="{{ asset("storage/images/clients.webp") }}"
                    alt="{{ __("store.Clients") }}"
                />
            </div>
            <p class="my-7">Don’t hesitate to contact us</p>
            <form class="flex flex-col gap-y-4">
                <x-product.contact-input
                    placeholder="{{ __('store.Name') }}"
                    field="name"
                />
                <x-product.contact-input
                    placeholder="{{__('store.Email')}}"
                    field="email"
                    type="email"
                />
                <x-product.contact-textarea
                    placeholder="Message"
                    field="message"
                />

                <button
                    class="w-fit rounded-3xl bg-darkColor px-6 py-3 text-sm text-lightColor transition-colors duration-300 hover:bg-slateDarkColor"
                    type="submit"
                >
                    {{ __("store.Send Message") }}
                </button>
            </form>
        </div>
    </div>
</x-home.section-container>
