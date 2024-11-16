<?php

namespace App\Providers;

use App\Helpers\Money\UserCurrency;
use App\Models\Bundle;
use App\Models\Faq;
use App\Models\FaqSection;
use App\Models\Product;
use App\Observers\BundleObserver;
use App\Observers\FaqObserver;
use App\Observers\FaqSectionObserver;
use App\Observers\ProductObserver;
use Blade;
use Illuminate\Support\ServiceProvider;
use Money\Money;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Blade::stringable(function (Money $money) {
            return UserCurrency::moneyObjectInBlade($money);
        });

        Product::observe(ProductObserver::class);
        Faq::observe(FaqObserver::class);
        FaqSection::observe(FaqSectionObserver::class);
        Bundle::observe(BundleObserver::class);
    }
}
