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
        Schema::create('event_sponsor', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->integer('event_id');
            $table->integer('sponsor_id');
            $table->string('name');
            $table->longText('description');                            // non-monetary offer or description of needs
            $table->decimal('amount', total: 8, places: 2)->nullable(); // monetary offer
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_sponsor');
    }
};
