<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'driver_id',
        'origin',
        'destination',
        'status',
        'departure_date',
        'arrival_date',
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'arrival_date' => 'datetime',
    ];

    /**
     * Get the truck that owns the delivery.
     */
    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    /**
     * Get the driver that owns the delivery.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
