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
        Schema::create('event_set', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->integer('event_id');
            $table->integer('venue_id');
            $table->integer('event_stage_id');
            $table->integer('artist_id');
            $table->string('name');
            $table->dateTime('time');
            $table->integer('duration')->default(30); // in minutes
            $table->boolean('sideshow')->default(0); // used to track performances that are in the space but not on stage
            $table->timestamps();
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_set');
    }
};
