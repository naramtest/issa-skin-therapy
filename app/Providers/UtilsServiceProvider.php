<?php

// Add this to your existing AppServiceProvider.php or create a new UtilsServiceProvider.php

namespace App\Providers;

use App\Services\Utils\ArabicTransliterationService;
use Illuminate\Support\ServiceProvider;

class UtilsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register as singleton so it's reused throughout the request
        $this->app->singleton(ArabicTransliterationService::class, function (
            $app
        ) {
            $service = new ArabicTransliterationService();

            // You can add custom mappings specific to your business here
            $customMappings = config("transliteration.custom_mappings", []);
            if (!empty($customMappings)) {
                $service->addCustomMappings($customMappings);
            }

            return $service;
        });

        // Create an alias for easier access
        $this->app->alias(
            ArabicTransliterationService::class,
            "arabic.transliteration"
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
