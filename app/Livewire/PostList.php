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

    private const REQUIRED_POSTS = 9;
    private const POSTS_PER_PAGE = 9;
    #[Url]
    public ?string $categoryId = null;
    private PostCacheService $postCacheService;

    public function mount(PostCacheService $postCacheService): void
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

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }
}
