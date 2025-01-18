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
        Schema::create("countries", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->char("iso2", 2)->unique();
            $table->char("iso3", 3)->unique();
            $table->string("native")->nullable();
            $table->string("currency")->nullable();
            $table->string("currency_name")->nullable();
            $table->string("currency_symbol")->nullable();
            $table->string("phone_code")->nullable();
            $table->string("region")->nullable();
            $table->string("subregion")->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();
            $table->string("emoji")->nullable();
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });

        Schema::create("states", function (Blueprint $table) {
            $table->id();
            $table->foreignId("country_id")->constrained()->cascadeOnDelete();
            $table->string("name");
            $table->string("type")->nullable(); // state, province, etc.
            $table->string("state_code");
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();
            $table->boolean("is_active")->default(true);
            $table->timestamps();

            $table->unique(["country_id", "state_code"]);
        });

        Schema::create("cities", function (Blueprint $table) {
            $table->id();
            $table->foreignId("state_id")->constrained()->cascadeOnDelete();
            $table->string("name");
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();
            $table->boolean("is_active")->default(true);
            $table->timestamps();

            $table->unique(["state_id", "name"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("countries");
    }
};
