<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table("customers", function (Blueprint $table) {
            // Drop the existing foreign key constraint first
            $table->dropForeign(["user_id"]);

            // Modify the column to be nullable
            $table->foreignId("user_id")->nullable()->change();

            // Add back the foreign key with nullOnDelete
            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users")
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table("customers", function (Blueprint $table) {
            // Remove nullable and restore the original constraint
            $table->dropForeign(["user_id"]);

            $table->foreignId("user_id")->nullable(false)->change();

            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users")
                ->cascadeOnDelete();
        });
    }
};
