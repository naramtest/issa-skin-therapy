<?php

namespace App\View\Components;

use App\Services\Product\ProductCacheService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MegaMenuComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public ProductCacheService $productCacheService)
    {
        //
    }

    public function render(): View|Closure|string
    {
        $categories = $this->productCacheService->allProductCategories();
        $bundles = $this->productCacheService->allBundles();
        return view("components.layout.header.mega-menu", [
            "categories" => $categories,
            "bundles" => $bundles,
        ]);
    }
}
