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
        Schema::create("product_types", function (Blueprint $table) {
            $table->id();
            $table->json("name");
            $table->string("slug")->unique();
            $table->timestamps();
        });

        Schema::create("product_product_type", function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->constrained()->onDelete("cascade");
            $table
                ->foreignId("product_type_id")
                ->constrained()
                ->onDelete("cascade");
            $table->timestamps();

            $table->unique(
                ["product_id", "product_type_id"],
                "product_type_unique"
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("product_types");
        Schema::dropIfExists("product_product_type");
    }
};
