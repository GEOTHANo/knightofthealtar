<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use App\Enums\MassType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleWeekday extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'week_start',
        'week_end',
        'day',
        'mass_type',
        'mass_time',
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
            'week_start' => 'date',
            'week_end' => 'date',
            'day' => DayOfWeek::class,
            'mass_type' => MassType::class,
        ];
    }

    /**
     * Get the weekday schedule member records.
     */
    public function scheduleMembers(): HasMany
    {
        return $this->hasMany(WeekdayScheduleMember::class, 'weekday_schedule_id');
    }

    /**
     * Get members assigned to the weekday schedule.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'weekday_schedule_members')
            ->withPivot(['is_active', 'assigned_date', 'attendance_status'])
            ->withTimestamps();
    }

    /**
     * Scope the query to the current Monday-to-Saturday week.
     */
    public function scopeCurrentWeek(Builder $query): Builder
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $weekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY)->toDateString();

        return $query->whereBetween('week_start', [$weekStart, $weekEnd]);
    }

    /**
     * Scope the query to a specific day of the week.
     */
    public function scopeByDay(Builder $query, DayOfWeek|string $day): Builder
    {
        $dayValue = $day instanceof DayOfWeek ? $day->value : $day;

        return $query->where('day', $dayValue);
    }
}