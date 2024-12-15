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
        Schema::table("users", function (Blueprint $table) {
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
        });

        // Populate first_name and last_name from existing name field
        DB::table("users")
            ->whereNull("first_name")
            ->orderBy("id")
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    $nameParts = explode(" ", $user->name, 2);
                    DB::table("users")
                        ->where("id", $user->id)
                        ->update([
                            "first_name" => $nameParts[0] ?? "",
                            "last_name" => $nameParts[1] ?? "",
                        ]);
                }
            });

        Schema::table("users", function (Blueprint $table) {
            $table->string("first_name")->nullable(false)->change();
            $table->string("last_name")->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            //
        });
    }
};
