<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('serial_number_format');
            $table->string('serial_number_prefix')->nullable();
            $table->unsignedBigInteger('serial_number_serie')->nullable();
            $table->unsignedSmallInteger('serial_number_year')->nullable();
            $table->unsignedTinyInteger('serial_number_month')->nullable();
            $table->unsignedBigInteger('serial_number_count');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('serial_number_prefix');
            $table->dropColumn('serial_number_serie');
            $table->dropColumn('serial_number_year');
            $table->dropColumn('serial_number_month');
            $table->dropColumn('serial_number_count');
            $table->dropColumn('serial_number_format');
        });
    }
};
