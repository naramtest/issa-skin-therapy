<?php

namespace App\Providers;

use App\Services\Cart\CartPriceCalculator;
use App\Services\Cart\CartService;
use App\Services\Cart\CartTaxCalculator;
use App\Services\Cart\Redis\CartCostsRedisService;
use App\Services\Cart\Redis\CartCouponRedisService;
use App\Services\Cart\Redis\CartItemsRedisService;
use App\Services\Checkout\OrderService;
use App\Services\Currency\CurrencyService;
use App\Services\Shipping\ShippingZoneService;
use Illuminate\Support\ServiceProvider;

class SingletonProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(OrderService::class, function ($app) {
            return new OrderService($app->make(CurrencyService::class));
        });

        // Cart Services
        // Register Redis Services
        $this->app->singleton(CartCostsRedisService::class);
        $this->app->singleton(CartItemsRedisService::class);

        // Register Tax Calculator
        $this->app->singleton(CartTaxCalculator::class);

        // Register Price Calculator
        $this->app->singleton(CartPriceCalculator::class, function ($app) {
            return new CartPriceCalculator(
                $app->make(CartTaxCalculator::class),
                $app->make(CartCostsRedisService::class)
            );
        });

        // Register Cart Service
        $this->app->singleton(CartService::class, function ($app) {
            return new CartService(
                $app->make(CartPriceCalculator::class),
                $app->make(CartItemsRedisService::class),
                $app->make(CartCostsRedisService::class),
                $app->make(CartCouponRedisService::class)
            );
        });

        $this->app->singleton(ShippingZoneService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
