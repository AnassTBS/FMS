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
}
