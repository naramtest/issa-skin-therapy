<div {{$attributes->class(['relative   w-full overflow-hidden rounded-[50px] border-2 border-white bg-white  hover:text-white inline-block text-black uppercase'])}}>
    <div
        class="transition-all w-full h-full flex items-center justify-center text-center   duration-[800ms] py-4"
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
        {{$slot}}

        <div
            style="inset-block-start: -50%;
                            inset-inline-start: -25%;
                            "
            class="absolute inset-0 -z-0 bg-black transition-transform rounded-[50%] h-[200%] py-4  w-[150%] duration-800 ease-in-out -translate-y-[76%]"
            :class="{ 'button-on': hoverOn ,
                                'button-out': hoverOff
                            }"
            7
        ></div>
    </div>

</div>
