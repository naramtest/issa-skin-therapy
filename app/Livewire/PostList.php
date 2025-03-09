<?php

namespace App\Livewire;

use App\Services\Post\PostCacheService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;

    public const REQUIRED_POSTS = 6;
    private const POSTS_PER_PAGE = 6;
    #[Url(as: "category")]
    public ?string $categoryId = "-1";
    public Collection $categories;
    private PostCacheService $postCacheService;

    public function boot(PostCacheService $postCacheService)
    {
        $this->postCacheService = $postCacheService;
    }

    public function render()
    {
        return view("livewire.post-list");
    }

    #[Computed]
    public function paginatedPosts()
    {
        return $this->postCacheService->getPaginatedPosts(
            categoryIds: $this->categoryId != -1 ? [$this->categoryId] : null,
            perPage: self::POSTS_PER_PAGE
        );
    }

    #[Computed]
    public function displayPosts(): Collection
    {
        $posts = collect($this->paginatedPosts->items());
        $postsCount = $posts->count();

        if ($postsCount >= self::REQUIRED_POSTS) {
            return $posts->take(self::REQUIRED_POSTS);
        }

        // Create a collection that repeats posts to reach 9
        $repeatedPosts = new Collection();

        while ($repeatedPosts->count() < self::REQUIRED_POSTS) {
            foreach ($posts as $post) {
                $repeatedPosts->push($post);
                if ($repeatedPosts->count() >= self::REQUIRED_POSTS) {
                    break;
                }
            }
        }

        return $repeatedPosts->take(self::REQUIRED_POSTS);
    }

    public function filterByCategory($categoryId): void
    {
        $this->categoryId = $categoryId;
        $this->resetPage();
    }
}
