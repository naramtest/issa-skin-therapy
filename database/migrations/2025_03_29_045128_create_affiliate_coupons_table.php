<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("affiliate_coupons", function (Blueprint $table) {
            $table->id();
            $table->foreignId("affiliate_id")->constrained()->cascadeOnDelete();
            $table->foreignId("coupon_id")->constrained()->cascadeOnDelete();
            $table->decimal("commission_rate", 5, 2); // Commission as percentage (e.g., 10.00 for 10%)
            $table->boolean("is_active")->default(true);
            $table->date("starts_at")->nullable();
            $table->date("expires_at")->nullable();
            $table->timestamps();

            // Ensure unique combinations of affiliate and coupon
            $table->unique(["affiliate_id", "coupon_id"]);
        });

        // Add affiliate_coupon_id to coupon_usage table
        Schema::table("coupon_usage", function (Blueprint $table) {
            $table
                ->foreignId("affiliate_coupon_id")
                ->nullable()
                ->after("customer_id")
                ->constrained()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("affiliate_coupons");
    }
};
