<?php

namespace App\Providers;

use App\Models\Bundle;
use App\Models\Faq;
use App\Models\FaqSection;
use App\Models\Product;
use App\Observers\BundleObserver;
use App\Observers\FaqObserver;
use App\Observers\FaqSectionObserver;
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
    }
}
