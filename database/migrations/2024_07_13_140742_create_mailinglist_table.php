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
        Schema::create('mailinglist', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
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
        Schema::dropIfExists('mailinglist');
    }
};
