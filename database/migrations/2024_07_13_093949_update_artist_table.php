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
        Schema::table('artist', function (Blueprint $table) {
            $table->string('genre')->nullable();
            $table->boolean('booked_previously')->default(0);
            $table->date('formed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artist', function (Blueprint $table) {
            $table->dropColumn('genre');
            $table->dropColumn('booked_previously');
            $table->dropColumn('formed');
        });
    }
};
