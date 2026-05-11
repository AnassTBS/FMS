<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'date',
        'liters',
        'amount',
        'mileage',
        'fuel_station',
        'notes',
        'user_id',
        'distance_traveled',
        'real_consumption',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'liters' => 'double',
        'amount' => 'double',
        'mileage' => 'double',
        'distance_traveled' => 'double',
        'real_consumption' => 'double',
    ];

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
