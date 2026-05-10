<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-users', fn (User $user): bool => $user->isAdmin());
        Gate::define('view-activity-logs', fn (User $user): bool => $user->isAdmin());
        Gate::define('manage-maintenance', fn (User $user): bool => $user->isAdmin());
        Gate::define('manage-fuel-entries', fn (User $user): bool => $user->isAdmin() || $user->isDriver());

        if (Schema::hasTable('activity_logs')) {
            \App\Models\Delivery::observe(\App\Observers\DeliveryObserver::class);
            \App\Models\Truck::observe(\App\Observers\TruckObserver::class);
            \App\Models\Driver::observe(\App\Observers\DriverObserver::class);
            \App\Models\User::observe(\App\Observers\UserObserver::class);
            \App\Models\FuelEntry::observe(\App\Observers\FuelEntryObserver::class);
            \App\Models\Maintenance::observe(\App\Observers\MaintenanceObserver::class);
        }
    }
}
