<?php

namespace App\Models;

use App\Enums\StockStatus;
use App\Traits\HasInventory;
use App\Traits\HasPricing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use SoftDeletes, HasPricing, HasInventory;
    use HasTranslations;
    use InteractsWithMedia;

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
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = static::generateSKU();
            }
        });
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
}
