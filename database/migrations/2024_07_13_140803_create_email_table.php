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
        Schema::create('email', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->integer('email_account_id');
            $table->integer('email_thread_id');
            $table->string('api_id'); // gmail_api id to retreive email details
            $table->string('subject');
            $table->enum('state', ['unread', 'read', 'deleted'])->default('unread');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email');
    }
};
