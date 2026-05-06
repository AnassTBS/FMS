<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Truck;
use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@fms.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);

        // Seed Trucks
        Truck::create(['registration_number' => 'TR-1001-A', 'model' => 'Volvo FH16', 'capacity' => 25000, 'status' => 'available']);
        Truck::create(['registration_number' => 'TR-2002-B', 'model' => 'Scania R500', 'capacity' => 30000, 'status' => 'in_use']);
        Truck::create(['registration_number' => 'TR-3003-C', 'model' => 'Mercedes Actros', 'capacity' => 22000, 'status' => 'maintenance']);

        // Seed Drivers
        Driver::create(['name' => 'John Doe', 'license_number' => 'DL12345', 'phone' => '0612345678', 'status' => 'available']);
        Driver::create(['name' => 'Jane Smith', 'license_number' => 'DL67890', 'phone' => '0698765432', 'status' => 'busy']);
        Driver::create(['name' => 'Ahmed Al-Farsi', 'license_number' => 'DL11223', 'phone' => '0655443322', 'status' => 'inactive']);
    }
}
