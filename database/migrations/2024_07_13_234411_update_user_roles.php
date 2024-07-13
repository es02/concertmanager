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
        Schema::table('artist', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
            $table->dropColumn('password');
           // $table->dropColumn('remember_token');
            $table->string('user_id')->nullable();
        });

        Schema::table('venue', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
            $table->dropColumn('password');
           // $table->dropColumn('remember_token');
            $table->string('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
