@props(["disable" => false])

<div
    :class="{ 'cursor-default bg-[#6c6c6c] border-[#6c6c6c]': disabled , 'hover:text-darkColor bg-darkColor border-darkColor' : !disabled}"
    {{ $attributes->class(["relative w-full overflow-hidden rounded-[50px] border-2   py-4 uppercase text-white  "]) }}
    @toggle-disable.window="disabled = !disabled"
    x-data="{
        hoverOn: false,
        hoverOff: false,
        disabled: @js($disable),

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
    <div
        class="flex h-full w-full items-center justify-center text-center transition-all duration-[800ms]"
    >
        {{ $slot }}
        <div
            x-show="!disabled"
            style="inset-block-start: -50%; inset-inline-start: -25%"
            class="duration-800 absolute inset-0 -z-0 h-[200%] w-[150%] -translate-y-[76%] rounded-[50%] bg-white transition-transform ease-in-out"
            :class="{ 'button-on': hoverOn &&  !disabled,
                                'button-out': hoverOff &&  !disabled
                            }"
        ></div>
    </div>
</div>
