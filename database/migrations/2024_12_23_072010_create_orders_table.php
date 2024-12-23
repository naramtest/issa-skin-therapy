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
        Schema::create("orders", function (Blueprint $table) {
            $table->id();
            $table->string("order_number")->unique();
            $table->foreignId("customer_id")->constrained();
            $table
                ->foreignId("billing_address_id")
                ->constrained("customer_addresses");
            $table
                ->foreignId("shipping_address_id")
                ->constrained("customer_addresses");
            $table->string("status");
            $table->string("payment_status");
            $table->string("shipping_method")->nullable();
            $table->unsignedBigInteger("subtotal");
            $table->unsignedBigInteger("shipping_cost")->default(0);
            $table->unsignedBigInteger("total");
            $table->text("notes")->nullable();
            $table->string("currency_code", 5);
            $table->decimal("exchange_rate", 10, 6)->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("orders");
    }
};
