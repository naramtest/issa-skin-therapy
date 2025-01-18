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
        Schema::create("coupon_usages", function (Blueprint $table) {
            $table->id();
            $table->foreignId("coupon_id")->constrained()->cascadeOnDelete();
            $table->foreignId("order_id")->constrained()->cascadeOnDelete();
            $table->foreignId("customer_id")->constrained()->cascadeOnDelete();
            $table->decimal("discount_amount", 10, 2); //TODO: check to see if its enough with the type is fixed and you store money in subunit
            $table->timestamp("used_at");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("coupon_usages");
    }
};
