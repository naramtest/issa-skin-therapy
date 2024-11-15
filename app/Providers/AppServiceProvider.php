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
use Blade;
use Illuminate\Support\ServiceProvider;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Blade::stringable(function (Money $money) {
            //TODO: check if it changes when the language changes
            $currencies = new ISOCurrencies();
            $numberFormatter = new NumberFormatter(
                \App::currentLocale(),
                \NumberFormatter::CURRENCY
            );
            $moneyFormatter = new IntlMoneyFormatter(
                $numberFormatter,
                $currencies
            );
            return $moneyFormatter->format($money);
        });

        Product::observe(ProductObserver::class);
        Faq::observe(FaqObserver::class);
        FaqSection::observe(FaqSectionObserver::class);
        Bundle::observe(BundleObserver::class);
    }
}
