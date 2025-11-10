<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $fillable = [
        'serial_number',
        'name',
        'equipment_type',
        'model',
        'brand', 
        'capacity',
        'status',
        'current_location',
        'latitude',
        'longitude',
        'last_maintenance_date',
        'next_maintenance_date',
        'notes',
        'is_active'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_maintenance_date' => 'date',
            'next_maintenance_date' => 'date',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8'
        ];
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }
}