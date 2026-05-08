<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FuelEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'date',
        'liters',
        'amount',
        'mileage',
    ];

    protected $casts = [
        'date' => 'date',
        'liters' => 'double',
        'amount' => 'double',
        'mileage' => 'double',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}
