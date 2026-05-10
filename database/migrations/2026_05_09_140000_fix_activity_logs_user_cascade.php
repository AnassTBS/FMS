<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Change activity_logs.user_id FK from CASCADE to SET NULL.
     * This preserves audit history when a user is deleted.
     */
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Drop the existing FK constraint
            $table->dropForeign(['user_id']);

            // Re-add with SET NULL behaviour
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migration (restore CASCADE).
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
