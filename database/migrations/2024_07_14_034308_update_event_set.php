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
        Schema::table('event_set', function (Blueprint $table) {
            $table->renameColumn('name', 'artist_name');
        });

        Schema::table('event_stage', function (Blueprint $table) {
            $table->renameColumn('name', 'stage_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
