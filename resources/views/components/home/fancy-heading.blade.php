<div
    x-data="{
        startAnimation: false,
        init() {
            this.startAnimation = true
            setInterval(() => {
                this.startAnimation = false
                setTimeout(() => (this.startAnimation = true), 100)
            }, 8000)
        },
    }"
    class="w-fit"
>
    <h3 class="text-3xl font-[800] md:text-[2.75rem] md:leading-[48px]">
        <span class="mb-1 md:block">{{ __("dashboard.Skin") }}</span>
        <span class="relative mb-1 md:block">
            {{ __("dashboard.That Defies") }}
            <svg
                x-show="startAnimation"
                x-transition:enter="transition duration-100 ease-out"
                class="animated-underline absolute -bottom-4 left-0 w-full"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 500 150"
                preserveAspectRatio="none"
                style="height: 40px"
            >
                <path
                    d="M0,75 Q125,75 250,75 T500,85"
                    stroke="#D5E1D1"
                    stroke-width="12"
                    fill="none"
                    stroke-linecap="round"
                ></path>
            </svg>
        </span>
        <span class="md:block">{{ __("dashboard.Time") }}</span>
    </h3>
</div>
