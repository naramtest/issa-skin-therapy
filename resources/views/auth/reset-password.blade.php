<x-store-main-layout>
    <main class="flex h-full w-full flex-col items-center justify-center py-20">
        <h1 class="text-[95px] font-bold">{{ __("New Password") }}</h1>
        <div
            style="box-shadow: 0 0 38px 14px rgba(2, 8, 53, 0.06)"
            class="mt-6 w-[30%] rounded-2xl p-8"
        >
            <form method="POST" action="{{ route("password.update") }}">
                @csrf
                <input
                    type="hidden"
                    name="token"
                    value="{{ $request->route("token") }}"
                />

                <x-auth.components.input
                    label="{{ __('store.Email Address') }}"
                    placeholder="{{ __('store.Enter your email here ') }}"
                    type="email"
                    field="email"
                    :value="old('email', $request->email)"
                    :is-required="true"
                />

                <x-auth.components.input
                    label="{{ __('dashboard.Password') }}"
                    placeholder="{{ __('dashboard.Password') }}"
                    field="password"
                    type="password"
                    :is-required="true"
                />

                <x-auth.components.input
                    label="{{ __('dashboard.Confirm Password') }}"
                    placeholder="{{ __('dashboard.Confirm Password') }}"
                    type="password"
                    field="password_confirmation"
                    :is-required="true"
                />

                <x-general.button-black-animation class="!w-fit !py-2 px-6">
                    <button class="relative z-10" type="submit">
                        {{ __("store.Reset Password") }}
                    </button>
                </x-general.button-black-animation>
            </form>
        </div>
    </main>
</x-store-main-layout>
