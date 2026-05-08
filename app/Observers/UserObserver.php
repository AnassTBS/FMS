<?php

namespace App\Observers;

use App\Models\User;

use App\Models\ActivityLog;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        ActivityLog::log('user_created', "Created new user: {$user->name} ({$user->role})", $user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->wasChanged('role')) {
            ActivityLog::log('user_role_changed', "User {$user->name} role changed to {$user->role}", $user);
        } else {
            ActivityLog::log('user_updated', "Updated profile for user: {$user->name}", $user);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        ActivityLog::log('user_deleted', "Deleted user account: {$user->name}", $user);
    }
}
