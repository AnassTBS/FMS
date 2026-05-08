<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('database.default') === 'mysql') {
            // 1. Add 'on_delivery' to the allowed values first
            DB::statement("ALTER TABLE trucks MODIFY COLUMN status ENUM('available', 'in_use', 'on_delivery', 'maintenance') DEFAULT 'available'");
            
            // 2. Update existing data
            DB::table('trucks')->where('status', 'in_use')->update(['status' => 'on_delivery']);
            
            // 3. Remove 'in_use' from allowed values
            DB::statement("ALTER TABLE trucks MODIFY COLUMN status ENUM('available', 'on_delivery', 'maintenance') DEFAULT 'available'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE trucks MODIFY COLUMN status ENUM('available', 'in_use', 'maintenance') DEFAULT 'available'");
        }
    }
};
