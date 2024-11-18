<?php

namespace App\Livewire;

use App\Services\Product\ProductCacheService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class MoreProduct extends Component
{
    public int $selectedCategory = 1;
    public bool $isProducts = true;
    protected ProductCacheService $productCacheService;

    public function boot(ProductCacheService $productCacheService)
    {
        $this->productCacheService = $productCacheService;
    }

    public function render()
    {
        return view("livewire.more-product");
    }

    #[On("select-category")]
    public function selectCategory(int $selectedCategory): void
    {
        $this->selectedCategory = $selectedCategory;
        $this->isProducts = $this->selectedCategory !== -1;
        $this->dispatch("product-filtered");
    }

    #[Computed]
    public function products(): Collection
    {
        if ($this->selectedCategory === -1) {
            return $this->productCacheService->allBundles();
        }
        return $this->productCacheService
            ->allProducts()
            ->filter(function ($product) {
                return $product->categories->contains(
                    "id",
                    $this->selectedCategory
                );
            });
    }
}
