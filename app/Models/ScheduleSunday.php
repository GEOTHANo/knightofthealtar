<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleSunday extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'schedule_sunday';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mass_date',
        'mass_name',
        'mass_time',
        'notes',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mass_date' => 'date',
        ];
    }

    /**
     * Get the Sunday schedule member records.
     */
    public function scheduleMembers(): HasMany
    {
        return $this->hasMany(SundayScheduleMember::class, 'sunday_schedule_id');
    }

    /**
     * Get members assigned to the Sunday mass.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'sunday_schedule_members', 'sunday_schedule_id', 'member_id')
            ->withPivot(['is_active', 'assigned_date', 'attendance_status'])
            ->withTimestamps();
    }

    /**
     * Scope upcoming Sunday masses.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereDate('mass_date', '>=', now()->toDateString())->orderBy('mass_date');
    }
}