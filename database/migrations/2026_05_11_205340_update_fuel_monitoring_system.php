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
        if (!Schema::hasColumn('trucks', 'average_consumption')) {
            Schema::table('trucks', function (Blueprint $table) {
                $table->decimal('average_consumption', 8, 2)->default(35.00)->after('capacity');
            });
        }

        Schema::table('fuel_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('fuel_entries', 'fuel_station')) {
                $table->string('fuel_station')->nullable()->after('mileage');
            }
            if (!Schema::hasColumn('fuel_entries', 'notes')) {
                $table->text('notes')->nullable()->after('fuel_station');
            }
            if (!Schema::hasColumn('fuel_entries', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('notes')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('fuel_entries', 'distance_traveled')) {
                $table->decimal('distance_traveled', 10, 2)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('fuel_entries', 'real_consumption')) {
                $table->decimal('real_consumption', 8, 2)->nullable()->after('distance_traveled');
            }
            if (!Schema::hasColumn('fuel_entries', 'status')) {
                $table->enum('status', ['normal', 'warning', 'critical'])->default('normal')->after('real_consumption');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropColumn('average_consumption');
        });

        Schema::table('fuel_entries', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['fuel_station', 'notes', 'user_id', 'distance_traveled', 'real_consumption', 'status']);
        });
    }
};
