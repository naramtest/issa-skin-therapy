<x-store-main-layout>
    <main class="mx-auto min-h-[100vh] w-[75%] pt-10">
        <h1 class="text-[95px] font-bold">{{ __("store.My account") }}</h1>
        <div class="ms-4 mt-2 flex items-center gap-x-8">
            {{-- <x-general.button-white-animation --}}
            {{-- class="!w-fit !border-black !py-2 px-8 !normal-case" --}}
            {{-- > --}}
            {{-- <a --}}
            {{-- href="{{ route("login.index") }}" --}}
            {{-- class="relative z-[10]" --}}
            {{-- type="submit" --}}
            {{-- > --}}
            {{-- {{ __("store.Sign In") }} --}}
            {{-- </a> --}}
            {{-- </x-general.button-white-animation> --}}
            {{-- <x-general.button-white-animation --}}
            {{-- class="!w-fit border !border-black !py-2 px-8 !normal-case" --}}
            {{-- > --}}
            {{-- <a --}}
            {{-- href="{{ route("login.index") }}" --}}
            {{-- class="relative z-[10]" --}}
            {{-- type="submit" --}}
            {{-- > --}}
            {{-- {{ __("store.Sign In") }} --}}
            {{-- </a> --}}
            {{-- </x-general.button-white-animation> --}}
            {{-- <x-general.button-white-animation --}}
            {{-- class="!w-fit border !border-black !py-2 px-8 !normal-case" --}}
            {{-- > --}}
            {{-- <a --}}
            {{-- href="{{ route("login.index") }}" --}}
            {{-- class="relative z-[10]" --}}
            {{-- type="submit" --}}
            {{-- > --}}
            {{-- {{ __("store.Sign In") }} --}}
            {{-- </a> --}}
            {{-- </x-general.button-white-animation> --}}
            <form action="{{ route("logout") }}" method="post">
                @csrf
                <x-general.button-white-animation
                    class="!w-fit border !border-black !py-2 px-8 !normal-case"
                >
                    <button class="relative z-[10]" type="submit">
                        {{ __("store.Log out") }}
                    </button>
                </x-general.button-white-animation>
            </form>
        </div>
        <div class="mt-5 text-[1.1rem] text-[#787c8a]">
            <div class="inline-block">
                <p class="inline-block">
                    {{ __("store.Hello") }}
                    <span class="capitalize text-darkColor">
                        {{ $user->full_name }}
                    </span>
                    ( not
                    <span class="capitalize text-darkColor">
                        {{ $user->full_name }}
                    </span>
                    ?
                </p>
                <form
                    class="inline-block"
                    action="{{ route("logout") }}"
                    method="post"
                >
                    @csrf
                    <button class="ms-1 text-darkColor underline" type="submit">
                        Log out
                    </button>
                </form>
                <p class="inline-block">)</p>
            </div>
            <p class="mt-3">
                From your account dashboard you can view your
                <span class="text-darkColor underline">
                    <a href="/">recent orders</a>
                </span>
                , manage your
                <span class="me-[1px] text-darkColor underline">
                    <a href="/">shipping and billing addresses</a>
                </span>
                , and
                <span class="text-darkColor underline">
                    <a href="/">edit your password and account details</a>
                </span>
                .
            </p>
        </div>

        <a
            href="{{ route("storefront.index") }}"
            class="mt-20 flex items-center justify-center gap-x-2"
        >
            <x-icons.back-to-store class="h-4 w-4" />
            <p>{{ __("store.Back To Store") }}</p>
        </a>
    </main>
</x-store-main-layout>
