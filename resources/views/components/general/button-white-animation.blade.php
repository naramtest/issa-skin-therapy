<div
    {{ $attributes->class(["relative w-full overflow-hidden rounded-[50px] border-2 border-white bg-white uppercase text-black hover:text-white"]) }}
>
    <div
        class="flex h-full w-full items-center justify-center text-center transition-all duration-[800ms]"
        x-data="{
            hoverOn: false,
            hoverOff: false,
            mouseOn() {
                this.hoverOn = true
                this.hoverOff = false
            },
            mouseOut() {
                this.hoverOn = false
                this.hoverOff = true
            },
        }"
        @mouseenter="mouseOn()"
        @mouseleave="mouseOut()"
    >
        {{ $slot }}

        <div
            style="inset-block-start: -50%; inset-inline-start: -25%"
            class="duration-800 absolute inset-0 -z-0 h-[200%] w-[150%] -translate-y-[76%] rounded-[50%] bg-black py-4 transition-transform ease-in-out"
            :class="{ 'button-on': hoverOn ,
                                'button-out': hoverOff
                            }"
            7
        ></div>
    </div>
</div>
