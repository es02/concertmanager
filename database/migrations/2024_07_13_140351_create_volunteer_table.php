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
        Schema::create('volunteer', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('tenant_id');
            $table->string('name');
            $table->string('email'); //->unique() Need to handle this in logic due to multi-tenant
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->longText('bio')->nullable();
            $table->string('pic_url')->nullable();
            $table->string('location')->nullable();
            $table->rememberToken();
            $table->enum('state', ['active', 'pending', 'suspended', 'deleted'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer');
    }
};
