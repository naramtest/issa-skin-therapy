<?php

use App\Enums\Checkout\OrderStatus;
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
            $table->foreignId("coupon_id")->constrained()->cascadeOnDelete();
            $table
                ->foreignId("affiliate_coupon_id")
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId("order_id")->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger("order_total", 10, 2);
            $table->decimal("commission_rate", 5, 2);
            $table->decimal("commission_amount", 10, 2);
            $table->string("status")->default(OrderStatus::PENDING);
            $table->date("paid_at")->nullable();
            $table->timestamps();
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
