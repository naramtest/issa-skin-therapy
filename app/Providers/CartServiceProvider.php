<?php

namespace App\Providers;

use App\Services\Cart\CartCostsManager;
use App\Services\Cart\CartPriceCalculator;
use App\Services\Cart\CartService;
use App\Services\Cart\CartTaxCalculator;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CartCostsManager::class);
        $this->app->singleton(CartTaxCalculator::class);

        $this->app->singleton(CartPriceCalculator::class, function ($app) {
            return new CartPriceCalculator(
                $app->make(CartCostsManager::class),
                $app->make(CartTaxCalculator::class)
            );
        });

        $this->app->singleton(CartService::class, function ($app) {
            return new CartService(
                $app->make(CartPriceCalculator::class),
                $app->make(CartCostsManager::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
