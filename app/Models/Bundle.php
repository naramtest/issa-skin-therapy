<?php

namespace App\Models;

use App\Enums\ProductStatus;
use App\Enums\StockStatus;
use App\Traits\HasPricing;
use App\Traits\Inventory\HasBundleInventory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Bundle extends Model implements HasMedia
{
    use SoftDeletes, HasPricing, HasBundleInventory;
    use HasTranslations;
    use InteractsWithMedia;

    public array $translatable = ["name", "description"];

    protected $fillable = [
        "name",
        "slug",
        "description",
        "sku",
        "order",
        "status",
        "published_at",
        "auto_calculate_price",
        "regular_price",
        "sale_price",
        "sale_starts_at",
        "sale_ends_at",
        "is_sale_scheduled",
        "bundle_level_stock",
        "quantity",
        "low_stock_threshold",
        "track_quantity",
        "allow_backorders",
        "stock_status",
        "weight",
        "length",
        "width",
        "height",
    ];

    protected $casts = [
        "auto_calculate_price" => "boolean",
        "bundle_level_stock" => "boolean",
        "regular_price" => "integer",
        "sale_price" => "integer",
        "sale_starts_at" => "datetime",
        "sale_ends_at" => "datetime",
        "is_sale_scheduled" => "boolean",
        "quantity" => "integer",
        "low_stock_threshold" => "integer",
        "track_quantity" => "boolean",
        "allow_backorders" => "boolean",
        "stock_status" => StockStatus::class,
        "status" => ProductStatus::class,
        "published_at" => "datetime",
        "weight" => "decimal:3",
        "length" => "decimal:2",
        "width" => "decimal:2",
        "height" => "decimal:2",
    ];

    protected static function booted(): void
    {
        static::creating(function ($bundle) {
            if (empty($bundle->sku)) {
                $bundle->sku = static::generateSKU();
            }
        });

        static::saving(function (Bundle $bundle) {
            // Set published_at timestamp when status changes to published
            if (
                $bundle->status === ProductStatus::PUBLISHED &&
                empty($bundle->published_at)
            ) {
                $bundle->published_at = Carbon::now();
            }

            // Clear published_at when status changes to draft
            if (
                $bundle->status === ProductStatus::DRAFT &&
                $bundle->getOriginal("status") ===
                    ProductStatus::PUBLISHED->value
            ) {
                $bundle->published_at = null;
            }

            // Auto calculate price if enabled
            if ($bundle->auto_calculate_price) {
                $bundle->calculateTotalPrice();
            }

            $bundle->determineStockStatus($bundle->quantity);
        });
    }

    public function calculateTotalPrice(): void
    {
        if (!$this->auto_calculate_price) {
            return;
        }

        $totalRegularPrice = 0;
        $totalCurrentPrice = 0;

        foreach ($this->items as $item) {
            $product = $item->product;
            $quantity = $item->quantity;

            // Calculate regular price
            $totalRegularPrice += $product->regular_price * $quantity;

            // Calculate current price (considering sales)
            $currentPrice = $product->isOnSale()
                ? $product->sale_price
                : $product->regular_price;
            $totalCurrentPrice += $currentPrice * $quantity;
        }

        // Update bundle prices
        $this->regular_price = $totalRegularPrice;

        // Only set sale price if it's different from regular price
        if ($totalCurrentPrice < $totalRegularPrice) {
            $this->sale_price = $totalCurrentPrice;

            // If any product is on sale, check for the earliest end date
            $earliestEndDate = $this->items
                ->map(function ($item) {
                    return $item->product->sale_ends_at;
                })
                ->filter()
                ->min();

            if ($earliestEndDate) {
                $this->sale_ends_at = $earliestEndDate;
                $this->is_sale_scheduled = true;
            }
        } else {
            $this->sale_price = null;
            $this->sale_ends_at = null;
            $this->is_sale_scheduled = false;
        }
    }

    public function items(): HasMany
    {
        return $this->hasMany(BundleItem::class);
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
}
