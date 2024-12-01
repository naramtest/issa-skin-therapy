<div {{$attributes->class(['relative  py-4 w-full overflow-hidden rounded-[50px] border-2 border-white bg-white  hover:text-white inline-block text-black uppercase'])}}>
    <div
        class="transition-all w-full h-full flex items-center justify-center text-center   duration-[800ms]"
        x-data="{ hoverOn: false ,hoverOff:false,mouseOn(){
                    this.hoverOn = true;
                    this.hoverOff = false;
                },
                    mouseOut(){
                    this.hoverOn = false
                    this.hoverOff = true
                }
                }"
        @mouseenter="mouseOn()"
        @mouseleave="mouseOut()">
                        <span class="relative z-10 inline-block ">
                        {{ __("store.Shop Now") }}
                    </span>
        <div
            style="inset-block-start: -50%;
                            inset-inline-start: -25%;
                            "
            class="absolute inset-0 -z-0 bg-black transition-transform rounded-[50%] h-[200%]  w-[150%] duration-800 ease-in-out -translate-y-[76%]"
            :class="{ 'button-on': hoverOn ,
                                'button-out': hoverOff
                            }"
            7
        ></div>
    </div>

</div>
