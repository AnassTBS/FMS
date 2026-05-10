<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Truck extends Model
{
    use HasFactory;

    protected $fillable = ['registration_number', 'model', 'capacity', 'status'];

    /**
     * Get the deliveries for the truck.
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    /**
     * Get the fuel entries for the truck.
     */
    public function fuelEntries(): HasMany
    {
        return $this->hasMany(FuelEntry::class);
    }

    /**
     * Get the maintenance records for the truck.
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }
}
