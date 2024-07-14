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
        Schema::create('mailinglist_member', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->integer('mailinglist_id');
            $table->string('name');
            $table->string('email');
            $table->enum('state', ['active', 'unsubscribed', 'deleted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailinglist_member');
    }
};
