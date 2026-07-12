<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MassSunday extends Model
{
    protected $table = 'mass_sundays';

    protected $fillable = [
        'mass_name',
        'mass_time',
        'description',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}
