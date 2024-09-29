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
        Schema::table('event_application_parent', function (Blueprint $table) {
            $table->boolean('shortlisted')->default(0);
            $table->boolean('accepted')->default(0);
            $table->boolean('rejected')->default(0);
            $table->longText('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_application_parent', function (Blueprint $table) {
            $table->dropColumn('shortlisted');
            $table->dropColumn('accepted');
            $table->dropColumn('rejected');
            $table->dropColumn('reason');
        });
    }
};
