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
        Schema::table("coupons", function (Blueprint $table) {
            $table
                ->foreignId("affiliate_id")
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table
                ->decimal("commission_rate", 5, 2)
                ->nullable()
                ->comment("Commission rate in percentage");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("coupons", function (Blueprint $table) {
            $table->dropForeign(["affiliate_id"]);
            $table->dropColumn(["affiliate_id", "commission_rate"]);
        });
    }
};
