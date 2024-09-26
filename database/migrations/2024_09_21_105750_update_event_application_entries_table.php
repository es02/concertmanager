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
        Schema::table('event_application_entries', function (Blueprint $table) {
            $table->integer('event_application_parent_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_application_entries', function (Blueprint $table) {
            $table->dropColumn('event_application_parent_id');
        });
    }
};
