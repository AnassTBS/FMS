<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'description',
        'ip_address',
    ];

    /**
     * Get the user who performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Static helper to log an activity.
     */
    public static function log(string $action, ?string $description = null, ?Model $target = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'target_type' => $target ? class_basename($target) : null,
            'target_id' => $target ? $target->id : null,
            'description' => $description ?? "Performed {$action} action",
            'ip_address' => request()->ip(),
        ]);
    }
}
