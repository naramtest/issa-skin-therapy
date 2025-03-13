<?php

namespace App\Models;

use App\Contracts\Purchasable;
use App\Enums\ProductStatus;
use App\Enums\StockStatus;
use App\Models\Query\BundleQuery;
use App\Services\Bundle\BundleService;
use App\Services\Inventory\BundleInventoryManager;
use App\Traits\HasPurchasableMedia;
use App\Traits\Price\HasBundlePrice;
use App\Traits\Price\HasMoney;
use App\Traits\Price\HasPricing;
use App\Traits\Seo\HasDynamicSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

class Bundle extends Model implements HasMedia, Purchasable
{
    use SoftDeletes, HasPricing, HasBundlePrice, HasMoney;
    use HasTranslations;
    use HasPurchasableMedia;
    use HasDynamicSeo;

    public array $translatable = [
        "name",
        "description",
        "how_to_use_am",
        "how_to_use_pm",
        "extra_tips",
        "subtitle",
    ];

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
        "url",
        "how_to_use_am",
        "how_to_use_pm",
        "extra_tips",
        "subtitle",
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

    protected BundleInventoryManager $inventory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Inject BundleInventoryManager
        $this->inventory = app(BundleInventoryManager::class, [
            "bundle" => $this,
        ]);
    }

    protected static function booted(): void
    {
        static::creating(function (Bundle $bundle) {
            if (empty($bundle->sku)) {
                $bundle->sku = static::generateSKU();
            }
        });

        static::saving(function (Bundle $bundle) {
            $service = app(BundleService::class);
            $service->handleSaving($bundle);
        });
    }

    public function inventory(): BundleInventoryManager
    {
        return $this->inventory;
    }

    public function items(): HasMany
    {
        return $this->hasMany(BundleItem::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, "bundle_items")
            ->withPivot("quantity")
            ->withTimestamps();
    }

    public function newEloquentBuilder($query): BundleQuery
    {
        return new BundleQuery($query);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->stock_status;
    }

    public function getFacebookIdAttribute(): string
    {
        return "SKU-" . $this->sku;
    }
}
