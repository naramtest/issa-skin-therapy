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
    class="card-overlay relative flex min-h-[600px] w-full items-center justify-center py-20"
>
    <div class="post-width z-10">
        <div class="flex">
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
        <h1 class="mt-3 text-[5.5rem] font-bold leading-[95px] text-white">
            {{ $post->title }}
        </h1>
    </div>
</x-home.section-container>
