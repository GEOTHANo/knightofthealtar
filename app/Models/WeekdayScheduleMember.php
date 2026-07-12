<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeekdayScheduleMember extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'weekday_schedule_id',
        'member_id',
        'is_active',
        'assigned_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'assigned_date' => 'date',
        ];
    }

    /**
     * Get the weekday schedule.
     */
    public function weekdaySchedule(): BelongsTo
    {
        return $this->belongsTo(ScheduleWeekday::class);
    }

    /**
     * Get the member assigned to the weekday schedule.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Scope only active assignments.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}