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
        Schema::create('event_ticket_types', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->string('event_ticket_type');
            $table->decimal('ticket_price', total: 8, places: 2)->nullable();
            $table->boolean('free')->default(0);
            $table->integer('allocation');
            $table->enum('state', ['open', 'closed', 'deleted'])->default('closed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_ticket_types');
    }
};
