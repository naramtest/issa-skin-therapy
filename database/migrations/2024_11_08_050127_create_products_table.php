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
        Schema::create("products", function (Blueprint $table) {
            $table->id();
            $table->json("name");
            $table->string("slug")->unique();
            $table->json("description");

            $table->string("sku")->unique();

            // Pricing
            $table->decimal("regular_price", 10, 2);
            $table->decimal("sale_price", 10, 2)->nullable();
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
            $table->string("country_of_origin", 2)->nullable(); // ISO 2 country code

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index("sku");
            $table->index("stock_status");
            $table->index(["sale_starts_at", "sale_ends_at"]);
            $table->index("is_sale_scheduled");
        });

        // Add check constraint for valid stock status values
        DB::statement(
            "
            ALTER TABLE products ADD CONSTRAINT check_valid_stock_status
            CHECK (stock_status IN ('" .
                implode("','", array_column(StockStatus::cases(), "value")) .
                "'))
        "
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("products");
    }
};
