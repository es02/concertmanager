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
        Schema::table('event_application', function (Blueprint $table) {
            $table->dateTime('open')->default("2025-05-03 12:00:00");
            $table->dateTime('close')->default("2025-05-04 00:00:00");
            $table->longText('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_application', function (Blueprint $table) {
            $table->dropColumn('open');
            $table->dropColumn('close');
            $table->dropColumn('description');
        });
    }
};
