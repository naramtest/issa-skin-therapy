<?php

namespace App\Services\Info;

use App;
use Illuminate\Support\Facades\Cache;

class InfoCacheService
{
    /**
     * Cache duration in seconds (1 week)
     */
    const CACHE_DURATION = 2628000 * 4;

    /**
     * Cache keys
     */

    const CACHE_KEY_INFO = "info";

    /**
     * Get product FAQs (cached)
     */
    public function getInfo(): App\Models\Info
    {
        return Cache::remember(
            self::CACHE_KEY_INFO,
            self::CACHE_DURATION,
            fn() => App\Models\Info::first()
        );
    }

    public function clearInfoCache(): void
    {
        Cache::forget(self::CACHE_KEY_INFO);
    }
}
