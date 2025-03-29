<?php

namespace App\Providers;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Faq;
use App\Models\FaqSection;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Observers\BundleObserver;
use App\Observers\CategoryObserver;
use App\Observers\FaqObserver;
use App\Observers\FaqSectionObserver;
use App\Observers\OrderObserver;
use App\Observers\PostObserver;
use App\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;

class ObserverProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Faq::observe(FaqObserver::class);
        FaqSection::observe(FaqSectionObserver::class);
        Bundle::observe(BundleObserver::class);
        Post::observe(PostObserver::class);
        Category::observe(CategoryObserver::class);
        Order::observe(OrderObserver::class);
    }
}
