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
use App\Services\Store\Currency\CurrencyHelper;
use App\Services\Store\Currency\CurrencyService;
use Blade;
use Illuminate\Support\ServiceProvider;
use Money\Money;
use Swap\Builder;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton("currency", function ($app) {
            // Create cache pool for Swap
            $store = $app["cache"]->store();

            $builder = new Builder();

            $builder
                ->add("apilayer_fixer", [
                    "api_key" => config("services.fixer.api_key"),
                ])
                ->add("apilayer_currency_data", [
                    "api_key" => config("services.currency_layer"),
                ])
                ->add("apilayer_exchange_rates_data", [
                    "api_key" => config("services.exchange_rates_data"),
                ])
                ->build();

            return new CurrencyService($builder->build());
        });
    }

    public function boot(): void
    {
        Blade::stringable(function (Money $money) {
            return CurrencyHelper::moneyObjectInBlade($money);
        });

        Product::observe(ProductObserver::class);
        Faq::observe(FaqObserver::class);
        FaqSection::observe(FaqSectionObserver::class);
        Bundle::observe(BundleObserver::class);
    }
}
