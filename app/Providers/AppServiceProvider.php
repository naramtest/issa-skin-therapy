<?php

namespace App\Providers;

use App\Contracts\InventoryInterface;
use App\Helpers\Money\UserCurrency;
use App\Services\Currency\CurrencyHelper;
use App\Services\Currency\CurrencyService;
use App\Services\Inventory\InventoryManager;
use Blade;
use Illuminate\Support\ServiceProvider;
use Money\Money;
use Swap\Builder;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //        TODO: use Redis
        //        $redis = new \Redis();
        //        $redis->connect('127.0.0.1', 6379);
        //
        //        $cacheAdapter = new RedisAdapter($redis);
        //        $simpleCache = new Psr16Cache($cacheAdapter);
        $this->app->singleton(CurrencyService::class, function ($app) {
            $builder = new Builder([
                "cache_ttl" => 3600,
                "cache_key_prefix" => "currency-",
            ]);
            $cacheAdapter = new FilesystemAdapter();
            $simpleCache = new Psr16Cache($cacheAdapter);
            $builder
                ->useSimpleCache($simpleCache)
                ->add("apilayer_fixer", [
                    "api_key" => config("services.fixer"),
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

        $this->app->bind(InventoryInterface::class, function ($app, $params) {
            return new InventoryManager($params["product"]);
        });
    }

    public function boot(): void
    {
        Blade::stringable(function (Money $money) {
            return CurrencyHelper::moneyObjectInBlade($money);
        });
    }
}
