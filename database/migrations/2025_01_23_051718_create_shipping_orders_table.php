<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("shipping_orders", function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained()->cascadeOnDelete();
            $table->string("carrier");
            $table->string("service_code");
            $table->string("tracking_number");
            $table->string("label_url")->nullable();
            $table->text("shipping_label_data")->nullable();
            $table->json("carrier_response")->nullable();
            $table->decimal("weight", 10, 3)->nullable();
            $table->decimal("length", 10, 2)->nullable();
            $table->decimal("width", 10, 2)->nullable();
            $table->decimal("height", 10, 2)->nullable();
            $table->string("status")->default("pending");
            $table->timestamp("shipped_at")->nullable();
            $table->timestamp("delivered_at")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("shipping_orders");
    }
};
