<?php

use App\Enums\StockStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("products", function (Blueprint $table) {
            $table->id();
            $table->json("name");
            $table->string("slug")->unique();
            $table->json("description");
            $table->json("short_description"); // Moved up in order
            $table->string("sku")->unique();
            $table->integer("order")->default(0)->index();
            $table->boolean("is_featured")->default(false)->index(); // Moved up in order
            $table->string("status", 50)->default("draft")->index(); // Moved up in order
            $table->timestamp("published_at")->nullable(); // Moved up in order

            // Pricing
            $table->integer("regular_price");
            $table->integer("sale_price")->nullable();
            $table->timestamp("sale_starts_at")->nullable();
            $table->timestamp("sale_ends_at")->nullable();
            $table->boolean("is_sale_scheduled")->default(false);

            // Inventory
            $table->integer("quantity")->default(0);
            $table->integer("low_stock_threshold")->default(5);
            $table->boolean("track_quantity")->default(true);
            $table->boolean("allow_backorders")->default(false);
            $table
                ->string("stock_status")
                ->default(StockStatus::OUT_OF_STOCK->value)
                ->comment(
                    "Possible values: " .
                        implode(
                            ", ",
                            array_column(StockStatus::cases(), "value")
                        )
                );

            // Physical attributes
            $table->decimal("weight", 8, 3)->nullable(); // in kg
            $table->decimal("length", 8, 2)->nullable(); // in cm
            $table->decimal("width", 8, 2)->nullable(); // in cm
            $table->decimal("height", 8, 2)->nullable(); // in cm

            // Shipping & International Trade
            $table->string("hs_code")->nullable();
            $table->string("country_of_origin", 50)->nullable(); // ISO

            // More Info
            $table->json("quick_facts_label")->nullable();
            $table->json("quick_facts_content")->nullable();
            $table->json("details")->nullable();
            $table->json("how_to_use")->nullable();
            $table->json("key_ingredients")->nullable();
            $table->json("full_ingredients")->nullable();
            $table->json("caution")->nullable();
            $table->json("how_to_store")->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index("sku");
            $table->index("stock_status");
            $table->index(["sale_starts_at", "sale_ends_at"]);
            $table->index("is_sale_scheduled");
        });

        // Add check constraint for valid stock status values
        if (config("database.default") === "mysql") {
            DB::statement(
                "ALTER TABLE products ADD CONSTRAINT check_valid_stock_status
                CHECK (stock_status IN ('" .
                    implode(
                        "','",
                        array_column(StockStatus::cases(), "value")
                    ) .
                    "'))"
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists("products");
    }
};
