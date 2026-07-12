<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MassWeekday extends Model
{
    protected $table = 'mass_weekdays';

    protected $fillable = [
        'day',
        'mass_type',
        'mass_time',
        'description',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}
