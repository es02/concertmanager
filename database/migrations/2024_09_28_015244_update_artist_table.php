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
            $table->integer('rating')->default(0);
            $table->boolean('blacklisted')->default(0);
            $table->longText('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artist', function (Blueprint $table) {
            $table->dropColumn('rating');
            $table->dropColumn('blacklisted');
            $table->dropColumn('notes');
        });
    }
};
