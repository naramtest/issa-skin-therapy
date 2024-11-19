<?php

use App\Enums\StockStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("bundles", function (Blueprint $table) {
            $table->id();
            $table->json("name");
            $table->string("slug")->unique();
            $table->json("description");
            $table->string("sku")->unique();
            $table->integer("order")->default(0)->index();
            $table->string("status", 50)->default("draft")->index();
            $table->timestamp("published_at")->nullable();

            // Pricing
            $table->boolean("auto_calculate_price")->default(true);
            $table->integer("regular_price");
            $table->integer("sale_price")->nullable();
            $table->timestamp("sale_starts_at")->nullable();
            $table->timestamp("sale_ends_at")->nullable();
            $table->boolean("is_sale_scheduled")->default(false);

            // Inventory
            $table->boolean("bundle_level_stock")->default(false);
            $table->integer("quantity")->default(0);
            $table->integer("low_stock_threshold")->default(5);
            $table->boolean("track_quantity")->default(true);
            $table->boolean("allow_backorders")->default(false);
            $table
                ->string("stock_status")
                ->default(StockStatus::OUT_OF_STOCK->value);

            // Physical attributes
            $table->decimal("weight", 8, 3)->nullable(); // in kg
            $table->decimal("length", 8, 2)->nullable(); // in cm
            $table->decimal("width", 8, 2)->nullable(); // in cm
            $table->decimal("height", 8, 2)->nullable(); // in cm

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index("sku");
            $table->index("stock_status");
            $table->index(["sale_starts_at", "sale_ends_at"]);
            $table->index("is_sale_scheduled");
        });

        Schema::create("bundle_items", function (Blueprint $table) {
            $table->id();
            $table->foreignId("bundle_id")->constrained()->onDelete("cascade");
            $table->foreignId("product_id")->constrained()->onDelete("cascade");
            $table->integer("quantity")->default(1);
            $table->timestamps();

            $table->unique(
                ["bundle_id", "product_id"],
                "bundle_product_unique"
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("bundles");
    }
};
