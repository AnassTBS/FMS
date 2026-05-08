<?php

namespace App\Observers;

use App\Models\FuelEntry;
use App\Models\ActivityLog;

class FuelEntryObserver
{
    public function created(FuelEntry $fuelEntry): void
    {
        ActivityLog::log('fuel_entry_created', "Added fuel entry for truck {$fuelEntry->truck->registration_number}: {$fuelEntry->liters}L", $fuelEntry);
    }

    public function updated(FuelEntry $fuelEntry): void
    {
        ActivityLog::log('fuel_entry_updated', "Updated fuel entry for truck {$fuelEntry->truck->registration_number}", $fuelEntry);
    }

    public function deleted(FuelEntry $fuelEntry): void
    {
        ActivityLog::log('fuel_entry_deleted', "Removed fuel entry for truck {$fuelEntry->truck->registration_number}", $fuelEntry);
    }
}
