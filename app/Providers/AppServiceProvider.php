<?php

namespace App\Providers;

use App\Services\Currency\CurrencyHelper;
use App\Services\Currency\CurrencyService;
use App\Services\Info\InfoCacheService;
use Blade;
use Clockwork\Support\Laravel\ClockworkMiddleware;
use Clockwork\Support\Laravel\ClockworkServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Money\Money;
use Opcodes\LogViewer\Facades\LogViewer;
use Route;
use Swap\Builder;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(ClockworkServiceProvider::class);
        }

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

        //        $this->app->bind(InventoryInterface::class, function ($app, $params) {
        //            return new InventoryManager($params["product"]);
        //        });
    }

    public function boot(Kernel $kernel): void
    {
        Blade::stringable(function (Money $money) {
            return CurrencyHelper::moneyObjectInBlade($money);
        });
        View::composer(
            ["components.layout.*", "storefront.contact", "storefront.legal.*"],
            function ($view) {
                $view->with("info", app(InfoCacheService::class)->getInfo());
            }
        );

        if ($this->app->isLocal()) {
            $kernel->prependMiddleware(ClockworkMiddleware::class);
        }
        Model::preventsLazyLoading();
        Model::preventAccessingMissingAttributes();
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post("/livewire/update", $handle)
                ->middleware("web")
                ->prefix(LaravelLocalization::setLocale());
        });
        //TODO: change to checking for superadmin
        LogViewer::auth(function ($request) {
            return $request->user() &&
                in_array($request->user()->email, ["admin@admin.com"]);
        });
    }
}
