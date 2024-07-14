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
        Schema::create('position', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('tenant_id');
            $table->string('name');
            $table->boolean('paid');
            $table->boolean('event_level')->default(1);
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position');
    }
};
