<?php

namespace App\Livewire;

use App\Services\Product\ProductCacheService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class MoreProduct extends Component
{
    public int $currentProduct = -1;

    public int $selectedCategory = 1;
    protected ProductCacheService $productCacheService;

    public function boot(ProductCacheService $productCacheService)
    {
        $this->productCacheService = $productCacheService;
    }

    public function render()
    {
        return view("livewire.more-product");
    }

    #[On("post-created")]
    public function selectCategory(int $selectedCategory): void
    {
        $this->selectedCategory = $selectedCategory;
        $this->dispatch("product-filtered");
    }

    #[Computed]
    public function products(): Collection
    {
        return $this->productCacheService->all()->filter(function ($product) {
            return $product->categories->contains(
                "id",
                $this->selectedCategory
            );
        });
    }
}
