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
        Schema::create("shipping_zones", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->json("countries")->nullable();
            $table->boolean("is_all_countries")->default(false); // New field
            $table->integer("order")->default(0);
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });

        Schema::create("shipping_methods", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("shipping_zone_id")
                ->constrained()
                ->cascadeOnDelete();
            $table->string("method_type"); // flat_rate, free_shipping, local_pickup, dhl_express
            $table->string("title");
            $table->unsignedBigInteger("cost")->nullable(); // Store in subunits
            $table->json("settings")->nullable(); // Method-specific settings
            $table->boolean("is_active")->default(true);
            $table->integer("order")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("shipping_zones");
    }
};
