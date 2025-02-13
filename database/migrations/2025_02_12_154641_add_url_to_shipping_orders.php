<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("shipping_orders", function (Blueprint $table) {
            // Drop existing columns we don't need anymore
            $table->dropColumn([
                "service_code",
                "label_url",
                "shipping_label_data",
                "carrier_response",
                "weight",
                "length",
                "width",
                "height",
                "status",
                "shipped_at",
                "delivered_at",
            ]);

            // Add new tracking URL column
            $table->string("tracking_url")->nullable();
        });
    }

    public function down(): void
    {
        Schema::table("shipping_orders", function (Blueprint $table) {
            // Restore original columns
            $table->string("service_code")->nullable();
            $table->string("label_url")->nullable();
            $table->text("shipping_label_data")->nullable();
            $table->json("carrier_response")->nullable();
            $table->decimal("weight", 8, 3)->nullable();
            $table->decimal("length", 8, 2)->nullable();
            $table->decimal("width", 8, 2)->nullable();
            $table->decimal("height", 8, 2)->nullable();
            $table->string("status")->nullable();
            $table->timestamp("shipped_at")->nullable();
            $table->timestamp("delivered_at")->nullable();

            // Remove the new tracking URL column
            $table->dropColumn("tracking_url");
        });
    }
};
