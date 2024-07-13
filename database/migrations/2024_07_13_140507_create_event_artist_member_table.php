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
        Schema::create('event_artist_member', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->integer('artist_id');
            $table->string('name');
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_artist_member');
    }
};
