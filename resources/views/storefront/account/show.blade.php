<x-account.layout :user="$user">
    <div class="mt-8 text-[1.1rem] text-[#787c8a]">
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
            {{ __("store.From your account dashboard you can view your") }}
            <span class="text-darkColor underline">
                <a href="/">{{ __("store.recent orders") }}</a>
            </span>
            , {{ __("store.manage your") }}
            <span class="me-[1px] text-darkColor underline">
                <a href="/">
                    {{ __("store.shipping and billing addresses") }}
                </a>
            </span>
            , {{ __("store.and") }}
            <span class="text-darkColor underline">
                <a href="/">
                    {{ __("store.edit your password and account details") }}
                </a>
            </span>
            .
        </p>
    </div>
</x-account.layout>
