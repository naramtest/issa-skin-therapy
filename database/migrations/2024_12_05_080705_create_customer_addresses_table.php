<?php

use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("customer_addresses", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignIdFor(Customer::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string("name");
            $table->string("phone")->nullable();
            $table->text("address");
            $table->string("city")->nullable();
            $table->string("country")->nullable();
            $table->string("postal_code")->nullable();
            $table->boolean("is_default")->default(false);
            $table->boolean("is_billing")->default(false);
            $table->timestamp("last_used_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("customer_addresses");
    }
};
