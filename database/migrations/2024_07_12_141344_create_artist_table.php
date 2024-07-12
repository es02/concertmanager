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
        Schema::create('artist', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->string('name');
            $table->string('email'); //->unique() Need to handle this in logic due to multi-tenant
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->longText('bio')->nullable();
            $table->string('pic_url')->nullable();
            $table->string('location')->nullable();
            $table->string('standard_fee')->nullable();
            $table->longText('standard_rider')->nullable();
            $table->longText('tech_specs')->nullable();
            $table->string('epk_url')->nullable();
            $table->integer('booking_agent_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist');
    }
};
