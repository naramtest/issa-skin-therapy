<x-store-main-layout>
    <main class="padding-from-side-menu py-20">
        <h1 class="text-[6rem] font-[800] leading-[100px]">FAQ's</h1>
        <p class="mt-3 w-[35%] text-[15px]">
            {{ __("store.Welcome to our FAQ") }}
        </p>
        <section class="flex gap-5 pt-6">
            <div class="w-[70%]">
                <ul class="flex flex-col gap-y-6 text-darkColor">
                    @foreach ($faqSections as $faqSection)
                        <x-faq.faq-section-item :faqSection="$faqSection" />
                    @endforeach
                </ul>
            </div>
            <div class="relative w-[30%] text-darkColor">
                <div class="sticky top-[90px]">
                    <h2 class="text-[23px] font-[700]">
                        {{ __("store.Didn’t find your answer?") }}
                    </h2>
                    <p class="mt-3">
                        {{ __("store.Don’t hesitate to contact us") }}Don’t
                        hesitate to contact us
                    </p>
                    <form class="mt-10 flex flex-col gap-4">
                        <x-faq.input
                            field="name"
                            label="{{ __('store.name') }}"
                            placeholder="{{ __('store.Your Name') }}"
                        />
                        <x-faq.input
                            field="email"
                            type="email"
                            label="{{ __('store.Email') }}"
                            placeholder="{{ __('store.Enter Your Email') }}"
                        />
                        <x-faq.text-area />
                    </form>
                </div>
            </div>
        </section>
    </main>
</x-store-main-layout>
