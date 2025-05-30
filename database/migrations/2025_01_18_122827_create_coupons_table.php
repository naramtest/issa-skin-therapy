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
        Schema::create("coupons", function (Blueprint $table) {
            $table->id();
            $table->string("code")->unique();
            $table->string("description")->nullable();
            $table->string("discount_type");
            $table->decimal("discount_amount", 10, 2); //TODO: check to see if its enough with the type is fixed and you store money in subunit
            $table->unsignedBigInteger("minimum_spend")->nullable();
            $table->unsignedBigInteger("maximum_spend")->nullable();
            $table->integer("usage_limit")->nullable();
            $table->integer("used_count")->default(0);
            $table->boolean("is_active")->default(true);
            $table->timestamp("starts_at")->nullable();
            $table->timestamp("expires_at")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("coupons");
    }
};
