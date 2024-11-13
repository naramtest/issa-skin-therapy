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
        Schema::create("faq_sections", function (Blueprint $table) {
            $table->id();
            $table->json("title");
            $table->json("description")->nullable();
            $table->integer("sort_order")->default(0);
            $table->boolean("is_active")->default(true);
            $table->boolean("is_product_section")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("faq_sections");
    }
};
