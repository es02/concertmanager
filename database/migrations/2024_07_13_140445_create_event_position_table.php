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
        Schema::create('event_position', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('tenant_id');
            $table->integer('event_id');
            $table->integer('event_stage_id')->nullable();
            $table->integer('position_id');
            $table->integer('user_id')->nullable();
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_position');
    }
};
