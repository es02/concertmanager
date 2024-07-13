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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->integer('event_id')->nullable();
            $table->integer('artist_id')->nullable();
            $table->integer('sponsor_id')->nullable();
            $table->integer('media_id')->nullable();
            $table->integer('venue_id')->nullable();
            $table->enum('type', ['media', 'artist', 'sponsor', 'venue', 'host']);
            $table->string('name');
            $table->enum('state', ['issued', 'pending', 'paid', 'deleted'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
