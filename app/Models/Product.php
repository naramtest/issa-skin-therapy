<?php

namespace App\Models;

use App\Enums\ProductStatus;
use App\Enums\StockStatus;
use App\Traits\HasInventory;
use App\Traits\HasMoney;
use App\Traits\HasPricing;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use SoftDeletes, HasPricing, HasInventory, HasMoney;
    use HasTranslations;
    use InteractsWithMedia;
    use HasTags;

    public array $sortable = [
        "order_column_name" => "order",
    ];

    public array $translatable = [
        "name",
        "description",
        "quick_facts_label",
        "quick_facts_content",
        "details",
        "how_to_use",
        "key_ingredients",
        "full_ingredients",
        "caution",
        "how_to_store",
        "short_description",
    ];
    protected $fillable = [
        "name",
        "slug",
        "sku",
        "regular_price",
        "sale_price",
        "sale_starts_at",
        "sale_ends_at",
        "is_sale_scheduled",
        "quantity",
        "short_description",
        "low_stock_threshold",
        "track_quantity",
        "allow_backorders",
        "stock_status",
        "weight",
        "length",
        "width",
        "height",
        "hs_code",
        "country_of_origin",
        "quick_facts_label",
        "quick_facts_content",
        "details",
        "how_to_use",
        "key_ingredients",
        "full_ingredients",
        "caution",
        "how_to_store",
        "is_featured",
        "status",
        "order",
        "published_at",
    ];

    protected $casts = [
        "regular_price" => "decimal:2",
        "sale_price" => "decimal:2",
        "sale_starts_at" => "datetime",
        "sale_ends_at" => "datetime",
        "is_sale_scheduled" => "boolean",
        "quantity" => "integer",
        "low_stock_threshold" => "integer",
        "track_quantity" => "boolean",
        "allow_backorders" => "boolean",
        "weight" => "decimal:3",
        "length" => "decimal:2",
        "width" => "decimal:2",
        "height" => "decimal:2",
        "stock_status" => StockStatus::class,
        "is_featured" => "boolean",
        "status" => ProductStatus::class,
        "published_at" => "datetime",
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = static::generateSKU();
            }
        });

        static::saving(function ($product) {
            if ($product->is_featured) {
                static::where("id", "!=", $product->id)
                    ->where("is_featured", true)
                    ->update(["is_featured" => false]);
            }
            // Set published_at timestamp when status changes to published
            if (
                $product->status === ProductStatus::PUBLISHED &&
                empty($product->published_at)
            ) {
                $product->published_at = Carbon::now();
            }

            // Clear published_at when status changes to draft
            if (
                $product->status === ProductStatus::DRAFT &&
                $product->getOriginal("status") ===
                    ProductStatus::PUBLISHED->value
            ) {
                $product->published_at = null;
            }
        });
    }

    /**
     * Update prices of associated auto-calculated bundles
     */
    public function updateBundlePrices(): void
    {
        $bundles = $this->autoCalculatedBundles()->get();

        foreach ($bundles as $bundle) {
            $bundle->calculateTotalPrice();
            $bundle->save();
        }
    }

    /**
     * Get bundles with auto-calculate price enabled
     */
    public function autoCalculatedBundles(): BelongsToMany
    {
        return $this->bundles()->where("auto_calculate_price", true);
    }

    public function updateStockStatus(): void
    {
        $newStatus = match (true) {
            !$this->track_quantity => StockStatus::IN_STOCK,
            $this->quantity <= 0 && $this->allow_backorders
                => StockStatus::BACKORDER,
            $this->quantity <= 0 => StockStatus::OUT_OF_STOCK,
            $this->quantity <= $this->low_stock_threshold
                => StockStatus::LOW_STOCK,
            default => StockStatus::IN_STOCK,
        };

        $this->update(["stock_status" => $newStatus]);
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(ProductType::class);
    }

    public function isAvailableForPurchase(): bool
    {
        return $this->stock_status->isAvailableForPurchase();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion(config("const.media.thumbnail"))
            ->format("webp")
            ->performOnCollections(config("const.media.featured"))
            ->width(400)
            ->height(400)
            ->optimize()
            ->quality(70);
        $this->addMediaConversion(config("const.media.optimized"))
            ->format("webp")
            ->optimize()
            ->withResponsiveImages();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(config("const.media.featured"))->singleFile();

        $this->addMediaCollection(config("const.media.gallery"));
    }

    public function categories(): morphToMany
    {
        return $this->morphToMany(Category::class, "model", "categorizables");
    }

    public function scopePublished($query)
    {
        return $query
            ->where("status", ProductStatus::PUBLISHED)
            ->whereNotNull("published_at")
            ->where("published_at", "<=", now());
    }

    public function scopeDraft($query)
    {
        return $query->where("status", ProductStatus::DRAFT);
    }

    public function scopeFeatured($query)
    {
        return $query->where("is_featured", true);
    }

    public function isPublished(): bool
    {
        return $this->status === ProductStatus::PUBLISHED;
    }

    public function isDraft(): bool
    {
        return $this->status === ProductStatus::DRAFT;
    }

    public function isScheduled(): bool
    {
        return $this->status === ProductStatus::PUBLISHED &&
            $this->published_at &&
            $this->published_at->isFuture();
    }

    public function scopeByOrder($query)
    {
        return $query->orderBy("order");
    }
}
