<x-account.layout :user="$user">
    {{-- TODO: add alert when update successfully --}}
    <div class="mt-8 text-[1.1rem]">
        <form
            action="{{ route("user-profile-information.update") }}"
            method="post"
        >
            @csrf
            @method("PUT")
            <div class="grid grid-cols-2 gap-4">
                <x-auth.components.input
                    label="{{ __('store.First Name') }}"
                    :required="true"
                    placeholder="{{ __('store.First Name') }}"
                    field="first_name"
                    :is-required="true"
                    :value="old('first_name',$user->first_name)"
                />

                <x-auth.components.input
                    label="{{ __('store.Last Name') }}"
                    :required="true"
                    placeholder="{{ __('store.Last Name') }}"
                    field="last_name"
                    :is-required="true"
                    :value="old('last_name',$user->last_name)"
                />
            </div>
            <x-auth.components.input
                label="{{ __('store.name') }}"
                :required="true"
                placeholder="{{ __('store.name') }}"
                field="name"
                :is-required="true"
                :value="old('name',$user->name)"
            />
            <x-auth.components.input
                label="{{ __('store.Email Address') }}"
                :required="true"
                placeholder="{{ __('store.Email Address') }}"
                field="email"
                :is-required="true"
                type="email"
                :value="old('email',$user->email)"
            />

            <x-general.button-black-animation class="mt-10 !w-fit !py-2 px-6">
                <button class="relative z-10" type="submit">
                    {{ __("store.Save Changes") }}
                </button>
            </x-general.button-black-animation>
        </form>

        <form
            class="mt-16"
            method="POST"
            action="{{ route("user-password.update") }}"
        >
            @csrf
            @method("PUT")
            <x-auth.components.input
                label="{{ __('store.Current Password') }}"
                placeholder="{{ __('store.Current Password') }}"
                field="password"
                type="password"
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

        {{-- TODO: add email prefernce --}}
    </div>
</x-account.layout>
