@props([
    /**@var\App\Models\Post*/"post",
])

<x-home.section-container
    style="
                background-image: url('{{ \App\Helpers\Media\ImageGetter::getMediaUrl($post)}}');
                background-position: center center;
                background-repeat: no-repeat;
                background-size: cover;
            "
    class="card-overlay relative flex w-full items-center justify-center py-20 md:h-[400px] lg:h-[600px]"
>
    <div class="post-width z-10">
        <div class="flex justify-center">
            <x-blog.category-label :post="$post" class="bg-secondaryColor" />
            <div class="ms-5 flex items-center text-white">
                <img
                    class="h-4 w-4"
                    src="{{ asset("storage/icons/calendar.svg") }}"
                    alt="{{ __("dashboard.Calender Icon") }}"
                />
                <p class="ms-1 text-xs">
                    {{ formattedDate($post->published_at) }}
                </p>
            </div>
        </div>
        <h1
            class="mx-auto mt-3 w-[90%] text-center text-2xl font-bold text-white md:text-[2.5rem] md:font-[800] md:leading-[40px] lg:w-full lg:text-start lg:text-[5.5rem] lg:font-bold lg:leading-[95px]"
        >
            {{ $post->title }}
        </h1>
    </div>
</x-home.section-container>
