<?php

namespace App\Models;

use App\Contracts\Purchasable;
use App\Enums\ProductStatus;
use App\Enums\StockStatus;
use App\Models\Query\ProductQuery;
use App\Services\Inventory\InventoryManager;
use App\Services\Product\ProductService;
use App\Traits\HasPurchasableMedia;
use App\Traits\Price\HasMoney;
use App\Traits\Price\HasPricing;
use App\Traits\Seo\HasDynamicSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia, Purchasable
{
    use SoftDeletes, HasPricing, HasMoney;
    use HasTranslations;
    use HasTags;
    use HasPurchasableMedia;
    use Searchable;
    use HasDynamicSeo;

    public bool $asYouType = true;

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
    protected InventoryManager $inventory;
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

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Dependency Injection
        $this->inventory = app(InventoryManager::class, ["product" => $this]);
    }

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = $this->inventory()->generateSKU(Product::class);
            }
        });

        static::saving(function (Product $product) {
            $service = app(ProductService::class);
            $service->handleSaving($product);

            $product->stock_status = $product
                ->inventory()
                ->determineStockStatus();
        });
    }

    public function inventory(): InventoryManager
    {
        return $this->inventory;
    }

    public function bundles(): BelongsToMany
    {
        return $this->belongsToMany(Bundle::class, "bundle_items")
            ->withPivot("quantity")
            ->withTimestamps();
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(ProductType::class);
    }

    public function categories(): morphToMany
    {
        return $this->morphToMany(Category::class, "model", "categorizables");
    }

    public function newEloquentBuilder($query): ProductQuery
    {
        return new ProductQuery($query);
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

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toSearchableArray(): array
    {
        return [
            "id" => $this->id,
            "name_en" => $this->getTranslation("name", "en"),
            "name_ar" => $this->getTranslation("name", "ar"),
            "description_en" => $this->getTranslation("description", "en"),
            "description_ar" => $this->getTranslation("description", "ar"),
            "short_description_ar" => $this->getTranslation(
                "short_description",
                "ar"
            ),
            "short_description_en" => $this->getTranslation(
                "short_description",
                "en"
            ),
            "full_ingredients_en" => $this->getTranslation(
                "full_ingredients",
                "en"
            ),
            "full_ingredients_ar" => $this->getTranslation(
                "full_ingredients",
                "ar"
            ),
            "sku" => $this->sku,
            "status" => $this->status->value,
            "categories" => $this->categories->pluck("name")->implode(" "),
            "tags" => $this->tags->pluck("name")->implode(" "),
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return true;
        //        return $this->status === ProductStatus::PUBLISHED and
        //            !$this->published_at and
        //            !$this->published_at <= now();
    }

    public function getFacebookIdAttribute(): string
    {
        return "SKU-" . $this->sku;
    }
}
