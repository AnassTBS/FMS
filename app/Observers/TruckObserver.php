<?php

namespace App\Observers;

use App\Models\Truck;

use App\Models\ActivityLog;

class TruckObserver
{
    /**
     * Handle the Truck "created" event.
     */
    public function created(Truck $truck): void
    {
        ActivityLog::log('truck_created', "Added new truck: {$truck->registration_number}", $truck);
    }

    /**
     * Handle the Truck "updated" event.
     */
    public function updated(Truck $truck): void
    {
        if ($truck->wasChanged('status')) {
            ActivityLog::log('truck_status_changed', "Truck {$truck->registration_number} status changed to {$truck->status}", $truck);
        } else {
            ActivityLog::log('truck_updated', "Updated truck details for {$truck->registration_number}", $truck);
        }
    }

    /**
     * Handle the Truck "deleted" event.
     */
    public function deleted(Truck $truck): void
    {
        ActivityLog::log('truck_deleted', "Removed truck: {$truck->registration_number}", $truck);
    }
}
