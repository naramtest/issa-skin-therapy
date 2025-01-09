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
        Schema::table("orders", function (Blueprint $table) {
            $table->string("payment_provider")->nullable();
            $table->string("payment_intent_id")->nullable()->unique();
            $table->json("payment_method_details")->nullable();
            $table->timestamp("payment_authorized_at")->nullable();
            $table->timestamp("payment_captured_at")->nullable();
            $table->timestamp("payment_refunded_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("orders", function (Blueprint $table) {
            $table->dropColumn([
                "payment_provider",
                "payment_intent_id",
                "payment_method_details",
                "payment_authorized_at",
                "payment_captured_at",
                "payment_refunded_at",
            ]);
        });
    }
};
