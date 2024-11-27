<x-store-main-layout>
    <main>
        <x-post.header :post="$post" />
        <x-post.content :post="$post" :next="$nextPost" :past="$pastPost" />
        <section class="padding-from-side-menu bg-[#FAFAFA] py-16">
            <div class="post-width mx-auto">
                <h2 class="mb-10 text-5xl font-bold">
                    {{ __("store.Latest Stories") }}
                </h2>
                <div class="grid grid-cols-3 gap-10">
                    @foreach ($latestPosts as $latestPost)
                        <x-posts.grid-item :post="$latestPost" />
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</x-store-main-layout>
