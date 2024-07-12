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
        Schema::create('event_stage', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->integer('event_id');
            $table->integer('venue_id');
            $table->string('name');
            $table->longText('bio')->nullable();
            $table->dateTime('doors')->nullable(); // most likely will match event
            $table->dateTime('close')->nullable(); // however this may not be the case for multi-stage events
            $table->timestamps();
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_stage');
    }
};
