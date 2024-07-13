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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->integer('user_id');
            $table->integer('event_id');
            $table->string('name');
            $table->string('email');
            $table->longText('bio')->nullable();
            $table->string('pic_url')->nullable();
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
        Schema::dropIfExists('media');
    }
};
