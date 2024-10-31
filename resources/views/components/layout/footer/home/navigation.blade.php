<div {{ $attributes->class(["w-[50%]"]) }}>
    <div class="grid w-full grid-cols-3">
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
            <li class="mt-3">
                <a href="">
                    <span>+ 971585957616</span>
                </a>
            </li>
            <li class="mt-3">
                <a class="mt-3" href="">
                    <span class="font-medium">+ info@issaskintherapy.com</span>
                </a>
            </li>
        </ul>
    </ul>
</div>
