<div {{ $attributes->class(["w-[50%]"]) }}>
    <div class="grid w-full grid-cols-3">
        <x-layout.footer.home.navigation-item
            title="Profile"
            :items="\App\Services\Nav::pages()"
        />
        <div>
            <h3 class="text-xl font-semibold">Customer Service</h3>
            <ul class="ms-1 mt-4 text-[0.8rem] font-light leading-[20px]">
                <li class="mt-3">Home</li>
                <li class="mt-3">Shop</li>
                <li class="mt-3">My Account</li>
                <li class="mt-3">Checkout</li>
                <li class="mt-3">Order Tracking</li>
            </ul>
        </div>
        <div>
            <h3 class="text-xl font-semibold">Profile</h3>
            <ul class="ms-1 mt-4 text-[0.8rem] font-light leading-[20px]">
                <li class="mt-3">Home</li>
                <li class="mt-3">Shop</li>
                <li class="mt-3">My Account</li>
                <li class="mt-3">Checkout</li>
                <li class="mt-3">Order Tracking</li>
            </ul>
        </div>
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
