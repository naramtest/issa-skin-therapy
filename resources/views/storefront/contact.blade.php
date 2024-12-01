<x-store-main-layout>
    <div class="flex gap-8 padding-from-side-menu pt-24">
        <div class="w-[50%]">
            <h1 class="text-[95px] font-[800]">Contact Us
            </h1>
            <form>
                <div class="mb-6 grid grid-cols-2 gap-4">
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
                <div class="mb-6 grid grid-cols-2 gap-4">
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
                    field="message" />
                <button
                    type="submit"
                    class="w-full mt-6"

                >
                    <x-general.button-black-animation>
                         <span class="relative z-10 inline-block ">
                                    {{ __("store.Shop Now") }}
                         </span>
                    </x-general.button-black-animation>
                </button>
            </form>
        </div>
        <div class=" w-[50%]
                ">
            <iframe loading="lazy"
                    class="w-full  h-[650px] rounded-xl"
                    src="https://maps.google.com/maps?q=West%20Chester%2C%20Pennsylvania&amp;t=m&amp;z=10&amp;output=embed&amp;iwloc=near"
                    title="West Chester, Pennsylvania" aria-label="West Chester, Pennsylvania"></iframe>
        </div>
    </div>
    <div class="grid grid-cols-4 padding-from-side-menu pb-24 pt-12 gap-4">
        <div class="border-gray-200 border rounded-xl p-7">
            <h3 class="text-[#B1B1B1] font-medium text-xs">{{ __('dashboard.info.address') }}</h3>
            <p class="text-sm  mt-3 font-medium">{{$info->address}}</p>
        </div>
        @if(count($info->email))
            <div class="border-gray-200 border rounded-xl p-7">
                <h3 class="text-[#B1B1B1] font-medium text-xs">{{ __('dashboard.EMAIL') }}</h3>
                <p class="text-sm  mt-3 font-medium">{{$info->email[0]['email']}}</p>
            </div>
        @endif
        @if(count($info->phone))

            <div class="border-gray-200 border rounded-xl p-7">
                <h3 class="text-[#B1B1B1] font-medium text-xs">{{ __('dashboard.WHATSAPP ONLY') }}</h3>
                <p class="text-sm  mt-3 font-medium">{{$info->phone[0]['number']}}</p>
            </div>
        @endif

        <div class="border-gray-200 border rounded-xl p-7">
            <h3 class="text-[#B1B1B1] font-medium text-xs">FOLLOW US</h3>
            <ul class="mt-3 flex w-2/3  items-center justify-between">
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
