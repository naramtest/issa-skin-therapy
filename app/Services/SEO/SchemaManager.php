<?php

namespace App\Services\SEO;

use App\Models\Info;
use App\Services\Info\InfoCacheService;
use App\Services\SEO\SchemaServices\AboutPageSchemaService;
use App\Services\SEO\SchemaServices\BundlePageSchemaService;
use App\Services\SEO\SchemaServices\ContactPageSchemaService;
use App\Services\SEO\SchemaServices\FaqPageSchemaService;
use App\Services\SEO\SchemaServices\HomePageSchemaService;
use App\Services\SEO\SchemaServices\ShopPageSchemaService;
use App\Services\SEO\SchemaServices\SingleProductSchemaService;
use Illuminate\Support\Facades\Cache;

class SchemaManager
{
    /**
     * Get schema for a given page with optional caching
     *
     * @param string $pageType
     * @param mixed|null $data
     * @param Info|null $info
     * @param bool $useCache
     * @return string
     */
    public function getSchema(
        string $pageType,
        mixed $data = null,
        ?Info $info = null,
        bool $useCache = true
    ): string {
        //        $cacheKey =
        //            "schema_{$pageType}_" . ($data ? md5(json_encode($data)) : "");
        //
        //        if ($useCache && !app()->isLocal()) {
        //            return Cache::remember($cacheKey, now()->addDay(), function () use (
        //                $pageType,
        //                $data
        //            ) {
        //                return $this->generateSchema($pageType, $data);
        //            });
        //        }

        if (!$info) {
            $info = app(InfoCacheService::class)->getInfo();
        }
        return $this->generateSchema($pageType, $data, $info);
    }

    /**
     * Generate schema based on page type
     *
     * @param string $pageType
     * @param mixed|null $data
     * @param Info|null $info
     * @return string
     */
    protected function generateSchema(
        string $pageType,
        mixed $data = null,
        ?Info $info = null
    ): string {
        $schemeService = match ($pageType) {
            "home" => app(HomePageSchemaService::class)->setFeaturedProduct(
                $data
            ),
            "shop" => app(ShopPageSchemaService::class)
                ->setBundles($data["bundles"])
                ->setProducts($data["products"]),
            "product" => app(SingleProductSchemaService::class)->setProduct(
                $data
            ),
            "bundle" => app(BundlePageSchemaService::class)->setBundle($data),
            "about" => app(AboutPageSchemaService::class),
            "contact" => app(ContactPageSchemaService::class),
            "faq" => app(FaqPageSchemaService::class)->setFaqSections($data),
        };
        return $schemeService->setInfo($info)->generate();
    }

    /**
     * Clear cached schemas
     *
     * @param string|null $pageType
     * @return void
     */
    public function clearCache(?string $pageType = null): void
    {
        if ($pageType) {
            Cache::forget("schema_{$pageType}");
        } else {
            // Clear all schema caches
            $keys = Cache::get("schema_keys", []);
            foreach ($keys as $key) {
                Cache::forget($key);
            }
            Cache::forget("schema_keys");
        }
    }
}
