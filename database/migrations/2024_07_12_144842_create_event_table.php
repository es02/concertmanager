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
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->string('name');
            $table->integer('venue_id');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->longText('description')->nullable();
            $table->string('ticketing_provider')->nullable();
            $table->boolean('free')->default(0);
            $table->boolean('all_ages')->default(0);
            $table->string('pic_url')->nullable();
            $table->string('ticket_url')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
