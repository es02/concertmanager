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
        Schema::create('plan', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', total: 8, places: 2)->nullable();
            $table->enum('period', ['monthly', 'annual'])->nullable();
            $table->boolean('trial')->default(0);
            $table->integer('trial_length')->nullable(); // measured in days
            $table->boolean('published')->default(0); // do we show this to users during signup?
            $table->boolean('default')->default(0); // If no plan is selected at signup do we just use this one?
            $table->timestamps();
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan');
    }
};
