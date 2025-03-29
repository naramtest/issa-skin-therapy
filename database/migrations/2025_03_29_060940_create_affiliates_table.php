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
        Schema::create("affiliates", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->nullable();
            $table->string("phone")->nullable();
            $table->string("slug")->unique();
            $table->text("about")->nullable();
            $table->boolean("status")->default(true);
            $table->unsignedBigInteger("total_commission")->default(0);
            $table->unsignedBigInteger("paid_commission")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("affiliates");
    }
};
