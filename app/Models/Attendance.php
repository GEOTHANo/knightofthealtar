<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    public const STATUS_PRESENT = 'Present';
    public const STATUS_LATE = 'Late';
    public const STATUS_ABSENT = 'Absent';
    public const STATUS_EXCUSED = 'Excused';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id',
        'meeting_week_start',
        'meeting_week_end',
        'attendance_date',
        'status',
        'remarks',
        'recorded_by',
        'date_recorded',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'meeting_week_start' => 'date',
            'meeting_week_end' => 'date',
            'attendance_date' => 'date',
            'date_recorded' => 'datetime',
            'status' => AttendanceStatus::class,
        ];
    }

    /**
     * Get the member being marked present, late, absent, or excused.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the member who recorded the attendance.
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'recorded_by');
    }

    /**
     * Scope attendance rows for a specific member.
     */
    public function scopeForMember(Builder $query, int $memberId): Builder
    {
        return $query->where('member_id', $memberId);
    }

    /**
     * Scope attendance rows with a present status.
     */
    public function scopePresent(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    /**
     * Scope attendance rows for the current Monday-to-Saturday week.
     */
    public function scopeCurrentWeek(Builder $query): Builder
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $weekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY)->toDateString();

        return $query->whereBetween('attendance_date', [$weekStart, $weekEnd]);
    }
}