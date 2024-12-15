<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("customers", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string("name");
            $table->string("email")->unique();
            $table->string("first_name");
            $table->string("last_name");
            $table->integer("orders_count")->default(0);
            $table->timestamp("last_order_at")->nullable();
            $table->decimal("total_spent", 10, 2)->default(0);
            $table->boolean("is_registered")->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("customers");
    }
};
