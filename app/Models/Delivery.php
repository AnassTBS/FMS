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
        'distance_km',
        'expected_fuel',
        'actual_fuel',
        'fuel_cost',
        'fuel_difference',
        'fuel_status',
        'status',
        'departure_date',
        'arrival_date',
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'arrival_date' => 'datetime',
        'distance_km' => 'double',
        'expected_fuel' => 'double',
        'actual_fuel' => 'double',
        'fuel_cost' => 'double',
        'fuel_difference' => 'double',
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

    /**
     * Calculate expected fuel based on truck consumption and distance.
     */
    public function calculateExpectedFuel(): void
    {
        if ($this->truck && $this->distance_km > 0) {
            $this->expected_fuel = ($this->truck->average_consumption * $this->distance_km) / 100;
        }
    }

    /**
     * Calculate fuel difference and status.
     */
    public function calculateFuelEfficiency(): void
    {
        if ($this->actual_fuel !== null && $this->expected_fuel > 0) {
            $this->fuel_difference = abs($this->actual_fuel - $this->expected_fuel);
            
            if ($this->fuel_difference <= 10) {
                $this->fuel_status = 'normal';
            } elseif ($this->fuel_difference <= 30) {
                $this->fuel_status = 'warning';
            } else {
                $this->fuel_status = 'critical';
            }
        }
    }

    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_ASSIGNED, self::STATUS_IN_TRANSIT], true);
    }
}
