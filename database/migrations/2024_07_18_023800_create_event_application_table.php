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
        Schema::create('event_application', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->integer('event_id');
            $table->string('name');
            $table->enum('type', ['artist', 'volunteer', 'sponsor', 'venue', 'media', 'staff'])->default('artist');
            $table->boolean('published')->default(0);
            $table->enum('state', ['open', 'closed', 'deleted'])->default('closed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_application');
    }
};
