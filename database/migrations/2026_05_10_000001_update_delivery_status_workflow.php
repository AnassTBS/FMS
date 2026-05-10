<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE deliveries MODIFY status ENUM('pending', 'in_progress', 'completed', 'assigned', 'in_transit', 'delivered') NOT NULL DEFAULT 'assigned'");
        }

        DB::table('deliveries')->where('status', 'pending')->update(['status' => 'assigned']);
        DB::table('deliveries')->where('status', 'in_progress')->update(['status' => 'in_transit']);
        DB::table('deliveries')->where('status', 'completed')->update(['status' => 'delivered']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE deliveries MODIFY status ENUM('assigned', 'in_transit', 'delivered') NOT NULL DEFAULT 'assigned'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE deliveries MODIFY status ENUM('pending', 'in_progress', 'completed', 'assigned', 'in_transit', 'delivered') NOT NULL DEFAULT 'pending'");
        }

        DB::table('deliveries')->where('status', 'assigned')->update(['status' => 'pending']);
        DB::table('deliveries')->where('status', 'in_transit')->update(['status' => 'in_progress']);
        DB::table('deliveries')->where('status', 'delivered')->update(['status' => 'completed']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE deliveries MODIFY status ENUM('pending', 'in_progress', 'completed') NOT NULL DEFAULT 'pending'");
        }
    }
};
