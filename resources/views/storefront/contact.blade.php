<x-store-main-layout>
    <div
        class="padding-from-side-menu flex flex-col gap-8 pt-12 md:flex-row lg:pt-24"
    >
        <div class="md:w-[50%]">
            <h1 class="text-4xl font-[800] lg:text-[95px] rtl:text-[60px]">
                {{ __("store.Contact Us") }}
            </h1>
            <form class="mt-10 md:mt-12">
                <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <x-checkout.input-field
                        label="{{ __('store.Name') }}:"
                        :required="true"
                        place-holder="{{ __('store.Type your name') }}"
                        field="name"
                    />

                    <x-checkout.input-field
                        label="{{ __('store.Email') }}:"
                        :required="true"
                        place-holder="{{ __('store.Type you email') }}"
                        field="email"
                        type="email"
                    />
                </div>
                <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <x-checkout.input-field
                        label="{{ __('dashboard.Phone number') }}:"
                        :required="true"
                        place-holder="{{ __('dashboard.Type your phone number') }}:"
                        field="phone_number"
                    />

                    <x-checkout.input-field
                        label="{{ __('dashboard.Subject') }}:"
                        :required="true"
                        place-holder="{{ __('dashboard.Email you used during checkout') }}"
                        field="subject"
                    />
                </div>
                <x-checkout.text-area-field
                    label="{{ __('store.Message') }}:"
                    :required="true"
                    placeHolder="{{ __('dashboard.Type your message') }}"
                    field="message"
                />
                <button type="submit" class="mt-6 w-full">
                    <x-general.button-black-animation>
                        <span class="relative z-10 inline-block">
                            {{ __("store.Shop Now") }}
                        </span>
                    </x-general.button-black-animation>
                </button>
            </form>
        </div>
        <div class="md:w-[50%]">
            <iframe
                loading="lazy"
                class="h-[650px] w-full rounded-xl"
                src="https://maps.google.com/maps?q=West%20Chester%2C%20Pennsylvania&amp;t=m&amp;z=10&amp;output=embed&amp;iwloc=near"
                title="West Chester, Pennsylvania"
                aria-label="West Chester, Pennsylvania"
            ></iframe>
        </div>
    </div>
    <div
        class="padding-from-side-menu grid grid-cols-1 gap-4 pb-24 pt-12 md:grid-cols-2 lg:grid-cols-4"
    >
        <div class="rounded-xl border border-gray-200 p-7">
            <h3 class="text-xs font-medium text-[#B1B1B1]">
                {{ __("dashboard.info.address") }}
            </h3>
            <p class="mt-3 text-sm font-medium">{{ $info->address }}</p>
        </div>
        @if (count($info->email))
            <div class="rounded-xl border border-gray-200 p-7">
                <h3 class="text-xs font-medium text-[#B1B1B1]">
                    {{ __("dashboard.EMAIL") }}
                </h3>
                <p class="mt-3 text-sm font-medium">
                    {{ $info->email[0]["email"] }}
                </p>
            </div>
        @endif

        @if (count($info->phone))
            <div class="rounded-xl border border-gray-200 p-7">
                <h3 class="text-xs font-medium text-[#B1B1B1]">
                    {{ __("dashboard.WHATSAPP ONLY") }}
                </h3>
                <p class="mt-3 text-sm font-medium">
                    {{ $info->phone[0]["number"] }}
                </p>
            </div>
        @endif

        <div class="rounded-xl border border-gray-200 p-7">
            <h3 class="text-xs font-medium text-[#B1B1B1]">
                {{ __("store.FOLLOW US") }}
            </h3>
            <ul class="mt-3 flex w-2/3 items-center justify-between">
                @foreach ($info->social as $social)
                    <x-share.icon
                        class="h-6 w-6"
                        name="{{$social['name']}}"
                        url="{{$social['url']}}"
                    />
                @endforeach
            </ul>
        </div>
    </div>
</x-store-main-layout>
