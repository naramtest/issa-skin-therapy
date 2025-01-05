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
        Schema::create("customer_emails", function (Blueprint $table) {
            $table->id();
            $table->foreignId("customer_id")->constrained()->cascadeOnDelete();
            $table->string("email");
            $table->boolean("is_verified")->default(false);
            $table->boolean("is_primary")->default(false);
            $table->timestamp("last_used_at")->nullable();
            $table->timestamps();

            $table->unique(["customer_id", "email"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("customer_emails");
    }
};
