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
        Schema::table("bundles", function (Blueprint $table) {
            $table->text("url")->nullable();
            $table->json("how_to_use_am")->nullable();
            $table->json("how_to_use_pm")->nullable();
            $table->json("extra_tips")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("bundles", function (Blueprint $table) {
            //
        });
    }
};
