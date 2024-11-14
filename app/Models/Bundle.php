<?php

namespace App\Models;

use App\Enums\ProductStatus;
use App\Enums\StockStatus;
use App\Traits\HasInventory;
use App\Traits\HasPricing;
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
    use SoftDeletes, HasPricing, HasInventory;
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

        static::saving(function ($bundle) {
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

            // Update stock status based on bundle items if bundle level stock is disabled
            if (!$bundle->bundle_level_stock) {
                $bundle->updateStockBasedOnItems();
            }
        });
    }

    public function calculateTotalPrice(): void
    {
        if (!$this->auto_calculate_price) {
            return;
        }

        $totalRegularPrice = $this->items->sum(function ($item) {
            return $item->product->regular_price * $item->quantity;
        });

        $totalSalePrice = $this->items->sum(function ($item) {
            return ($item->product->isOnSale()
                ? $item->product->sale_price
                : $item->product->regular_price) * $item->quantity;
        });

        $this->regular_price = $totalRegularPrice;
        $this->sale_price =
            $totalSalePrice < $totalRegularPrice ? $totalSalePrice : null;
    }

    public function updateStockBasedOnItems(): void
    {
        if ($this->bundle_level_stock) {
            return;
        }

        $lowestQuantity = $this->items->min(function ($item) {
            return floor($item->product->quantity / $item->quantity);
        });

        $newStatus = $this->determineStockStatus($lowestQuantity);

        $this->quantity = $lowestQuantity;
        $this->stock_status = $newStatus;
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
