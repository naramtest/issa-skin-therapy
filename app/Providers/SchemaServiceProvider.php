<?php

namespace App\Providers;

use App\Services\SEO\SchemaManager;
use App\Services\SEO\SchemaServices\AboutPageSchemaService;
use App\Services\SEO\SchemaServices\BundlePageSchemaService;
use App\Services\SEO\SchemaServices\HomePageSchemaService;
use App\Services\SEO\SchemaServices\ShopPageSchemaService;
use App\Services\SEO\SchemaServices\SingleProductSchemaService;
use Illuminate\Support\ServiceProvider;

class SchemaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register SchemaManager
        $this->app->singleton(SchemaManager::class);

        // Register all schema services
        $this->app->singleton(HomePageSchemaService::class);
        $this->app->singleton(ShopPageSchemaService::class);
        $this->app->singleton(SingleProductSchemaService::class);
        $this->app->singleton(BundlePageSchemaService::class);

        // Register our new schema services
        $this->app->singleton(AboutPageSchemaService::class);
        //        $this->app->singleton(ContactPageSchemaService::class);
        //        $this->app->singleton(FaqPageSchemaService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
