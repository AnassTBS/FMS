<?php

namespace App\Observers;

use App\Models\Delivery;

use App\Models\ActivityLog;

class DeliveryObserver
{
    /**
     * Handle the Delivery "created" event.
     */
    public function created(Delivery $delivery): void
    {
        ActivityLog::log('delivery_created', "Created delivery from {$delivery->origin} to {$delivery->destination}", $delivery);
    }

    /**
     * Handle the Delivery "updated" event.
     */
    public function updated(Delivery $delivery): void
    {
        if ($delivery->wasChanged('status')) {
            ActivityLog::log('delivery_status_changed', "Delivery status changed to {$delivery->status}", $delivery);
        } else {
            ActivityLog::log('delivery_updated', "Updated delivery details", $delivery);
        }
    }

    /**
     * Handle the Delivery "deleted" event.
     */
    public function deleted(Delivery $delivery): void
    {
        ActivityLog::log('delivery_deleted', "Deleted delivery from {$delivery->origin} to {$delivery->destination}", $delivery);
    }
}
