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
        Schema::create('event_event_guest', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->integer('event_id');
            $table->integer('artist_id')->nullable();
            $table->integer('sponsor_id')->nullable();
            $table->integer('media_id')->nullable();
            $table->integer('venue_id')->nullable();
            $table->enum('type', ['media', 'artist', 'sponsor', 'venue', 'host']);
            $table->string('name');
            $table->string('email');
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_event_guest');
    }
};
