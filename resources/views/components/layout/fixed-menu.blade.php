<ul
    {{ $attributes->class(["fixed z-[150] ms-5 mt-2 lg:flex hidden w-[60px] flex-col items-center justify-normal gap-y-6 rounded-[3rem] bg-[#E7E7E740] px-[4px] py-[18px] backdrop-blur-[5px] "]) }}
>
    @foreach ($info->social as $social)
        <x-share.icon
            class="h-[1.2rem] w-[1.2rem]"
            name="{{$social['name']}}"
            url="{{$social['url']}}"
        />
    @endforeach

    <div
        class="vertical-text mt-6 cursor-default rounded-[3rem] bg-[#DDE0E2] px-[10px] py-[30px] text-sm font-medium hover:bg-[#FAFAFA]"
        href=""
    >
        <span>{{ __("store.Follow Us") }}</span>
    </div>
</ul>
