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
        Schema::create("affiliate_commissions", function (Blueprint $table) {
            $table->id();
            $table->foreignId("affiliate_id")->constrained()->cascadeOnDelete();
            $table->foreignId("order_id")->constrained()->cascadeOnDelete();
            $table->foreignId("coupon_id")->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger("order_total");
            $table->decimal("commission_rate", 5, 2);
            $table->unsignedBigInteger("commission_amount");
            $table->string("status")->default("pending");
            $table->timestamp("paid_at")->nullable();
            $table->timestamps();

            // Ensure one commission per order
            $table->unique(["order_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("affiliate_commissions");
    }
};
