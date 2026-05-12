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
        Schema::table('deliveries', function (Blueprint $table) {
            if (!Schema::hasColumn('deliveries', 'distance_km')) {
                $table->decimal('distance_km', 10, 2)->nullable()->after('destination');
            }
            if (!Schema::hasColumn('deliveries', 'expected_fuel')) {
                $table->decimal('expected_fuel', 10, 2)->nullable()->after('distance_km');
            }
            if (!Schema::hasColumn('deliveries', 'actual_fuel')) {
                $table->decimal('actual_fuel', 10, 2)->nullable()->after('expected_fuel');
            }
            if (!Schema::hasColumn('deliveries', 'fuel_difference')) {
                $table->decimal('fuel_difference', 10, 2)->nullable()->after('fuel_cost');
            }
            if (!Schema::hasColumn('deliveries', 'fuel_status')) {
                $table->enum('fuel_status', ['normal', 'warning', 'critical'])->nullable()->after('fuel_difference');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            if (Schema::hasColumn('deliveries', 'fuel_status')) {
                $table->dropColumn('fuel_status');
            }
            if (Schema::hasColumn('deliveries', 'fuel_difference')) {
                $table->dropColumn('fuel_difference');
            }
            if (Schema::hasColumn('deliveries', 'actual_fuel')) {
                $table->dropColumn('actual_fuel');
            }
            if (Schema::hasColumn('deliveries', 'expected_fuel')) {
                $table->dropColumn('expected_fuel');
            }
            if (Schema::hasColumn('deliveries', 'distance_km')) {
                $table->dropColumn('distance_km');
            }
        });
    }
};

