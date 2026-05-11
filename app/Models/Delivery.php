<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_IN_TRANSIT = 'in_transit';
    public const STATUS_DELIVERED = 'delivered';

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

    public static function statuses(): array
    {
        return [
            self::STATUS_ASSIGNED,
            self::STATUS_IN_TRANSIT,
            self::STATUS_DELIVERED,
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_ASSIGNED => 'Assigned',
            self::STATUS_IN_TRANSIT => 'In Transit',
            self::STATUS_DELIVERED => 'Delivered',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Automatically synchronize the delivery status based on time rules.
     * Assigned: now < departure
     * In Transit: departure <= now < arrival
     * Delivered: now >= arrival
     */
    public function syncStatus(): void
    {
        $now = now();
        $newStatus = $this->status;

        if ($now < $this->departure_date) {
            $newStatus = self::STATUS_ASSIGNED;
        } elseif ($this->arrival_date && $now >= $this->arrival_date) {
            $newStatus = self::STATUS_DELIVERED;
        } elseif ($now >= $this->departure_date) {
            $newStatus = self::STATUS_IN_TRANSIT;
        }

        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            $this->save();
        }
    }

    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_ASSIGNED, self::STATUS_IN_TRANSIT], true);
    }
}
