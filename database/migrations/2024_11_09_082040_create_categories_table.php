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
        Schema::create("categories", function (Blueprint $table) {
            $table->id();
            $table->json("name");
            $table->string("slug")->unique();
            $table->integer("order")->default(0)->index();
            $table->boolean("is_visible")->default(true);
            $table->json("description")->nullable();
            $table->string("type", 50);
            $table->timestamps();
        });

        Schema::create("categorizables", function (Blueprint $table) {
            $table->integer("category_id");
            $table->morphs("model");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("categories");
    }
};
