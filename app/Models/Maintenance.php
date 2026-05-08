<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'description',
        'target_mileage',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'target_mileage' => 'double',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}
