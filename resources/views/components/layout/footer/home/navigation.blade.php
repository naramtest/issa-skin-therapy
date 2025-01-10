<div {{ $attributes->class(["lg:w-[50%]"]) }}>
    <div
        class="grid w-full grid-cols-1 items-center gap-y-10 lg:grid-cols-3 lg:items-start"
    >
        <x-layout.footer.home.navigation-item
            title="{{ __('store.Profile') }}"
            :items="\App\Services\Nav::pages()['profile']"
        />
        <x-layout.footer.home.navigation-item
            title="{{ __('store.Customer Service') }}"
            :items="\App\Services\Nav::pages()['customer']"
        />
        <x-layout.footer.home.navigation-item
            title="{{ __('store.Info') }}"
            :items="\App\Services\Nav::pages()['info']"
        />
    </div>
    <ul class="mt-6 text-xl font-medium">
        <ul>
            @unless (empty($info->phone))
                <li class="mt-3">
                    <a href="tel:{{ $info->phone[0]["number"] }}">
                        <span>+ {{ $info->phone[0]["number"] }}</span>
                    </a>
                </li>
            @endunless

            @unless (empty($info->phone))
                <li class="mt-3">
                    <a
                        class="mt-3"
                        href="mailto:{{ $info->email[0]["email"] }}"
                    >
                        <span class="font-medium">
                            {{ $info->email[0]["email"] }}
                        </span>
                    </a>
                </li>
            @endunless
        </ul>
    </ul>
</div>
