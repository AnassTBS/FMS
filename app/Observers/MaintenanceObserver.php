<?php

namespace App\Observers;

use App\Models\Maintenance;
use App\Models\ActivityLog;

class MaintenanceObserver
{
    public function created(Maintenance $maintenance): void
    {
        ActivityLog::log('maintenance_scheduled', "Scheduled maintenance for truck {$maintenance->truck->registration_number}: {$maintenance->description}", $maintenance);
    }

    public function updated(Maintenance $maintenance): void
    {
        if ($maintenance->wasChanged('is_completed') && $maintenance->is_completed) {
            ActivityLog::log('maintenance_completed', "Completed maintenance for truck {$maintenance->truck->registration_number}", $maintenance);
        } else {
            ActivityLog::log('maintenance_updated', "Updated maintenance details for truck {$maintenance->truck->registration_number}", $maintenance);
        }
    }

    public function deleted(Maintenance $maintenance): void
    {
        ActivityLog::log('maintenance_deleted', "Removed maintenance record for truck {$maintenance->truck->registration_number}", $maintenance);
    }
}
