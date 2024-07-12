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
        Schema::create('venue', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->string('name');
            $table->string('email'); //->unique() Need to handle this in logic due to multi-tenant
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->ingeger('capacity')->nullable();
            $table->longText('bio')->nullable();
            $table->string('pic_url')->nullable();
            $table->string('location')->nullable();
            $table->decimal('standard_fee', total: 8, places: 2)->nullable();
            $table->decimal('ticket_cut', total: 8, places: 2)->nullable();
            $table->enum('cut_type', ['per_ticket', 'percentage'])->nullable();
            $table->enum('fee_type', ['total', 'minimum'])->nullable();
            $table->longText('additional_fees')->nullable();
            $table->longText('tech_specs')->nullable();
            $table->longText('backline')->nullable();
            $table->timestamps();
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue');
    }
};
