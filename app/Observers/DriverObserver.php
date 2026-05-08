<?php

namespace App\Observers;

use App\Models\Driver;

use App\Models\ActivityLog;

class DriverObserver
{
    /**
     * Handle the Driver "created" event.
     */
    public function created(Driver $driver): void
    {
        ActivityLog::log('driver_created', "Registered new driver: {$driver->full_name}", $driver);
    }

    /**
     * Handle the Driver "updated" event.
     */
    public function updated(Driver $driver): void
    {
        if ($driver->wasChanged('status')) {
            ActivityLog::log('driver_status_changed', "Driver {$driver->full_name} status changed to {$driver->status}", $driver);
        } else {
            ActivityLog::log('driver_updated', "Updated driver details for {$driver->full_name}", $driver);
        }
    }

    /**
     * Handle the Driver "deleted" event.
     */
    public function deleted(Driver $driver): void
    {
        ActivityLog::log('driver_deleted', "Removed driver profile for {$driver->full_name}", $driver);
    }
}
