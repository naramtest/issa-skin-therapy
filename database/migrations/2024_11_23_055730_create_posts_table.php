<?php

use App\Enums\ProductStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("posts", function (Blueprint $table) {
            $table->id();
            $table->json("title");
            $table->string("slug");
            $table->json("body")->nullable();
            $table->enum("status", [
                ProductStatus::PUBLISHED->value,
                ProductStatus::DRAFT->value,
            ]);
            $table->boolean("edited")->default(false);
            $table->json("excerpt")->nullable();
            $table->json("meta_title")->nullable();
            $table->json("meta_description")->nullable();
            $table->foreignId("user_id")->nullable();
            $table->timestamp("published_at")->nullable();
            $table->timestamp("scheduled_at")->nullable();
            $table->uuid()->nullable();
            $table->boolean("is_featured")->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("posts");
    }
};
