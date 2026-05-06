<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'license_number', 'phone', 'status', 'user_id'];

    /**
     * Get the user that owns the driver profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the deliveries for the driver.
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }
}
