<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\MemberStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Member extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    public const STATUS_ACTIVE = 'Active';
    public const STATUS_INACTIVE = 'Inactive';
    public const STATUS_ALUMNI = 'Alumni';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'profile_picture',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'gender',
        'complete_address',
        'contact_number',
        'email_address',
        'school_attended',
        'mother_name',
        'mother_occupation',
        'father_name',
        'father_occupation',
        'number_of_siblings',
        'gkk',
        'date_of_acceptance',
        'batch_year',
        'date_added',
        'status',
        'is_deleted',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to model arrays.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'full_name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'date_of_acceptance' => 'date',
            'date_added' => 'datetime',
            'number_of_siblings' => 'integer',
            'batch_year' => 'integer',
            'is_deleted' => 'boolean',
            'gender' => Gender::class,
            'status' => MemberStatus::class,
        ];
    }

    /**
     * Get the member's full name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $parts = array_filter([
                    $this->first_name,
                    $this->middle_name,
                    $this->last_name,
                ]);

                return trim(implode(' ', $parts));
            }
        );
    }

    /**
     * Hash the member password before it is stored.
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: static function (?string $value): ?string {
                if ($value === null || $value === '') {
                    return $value;
                }

                return Hash::make($value);
            }
        );
    }

    /**
     * Get all positions assigned to the member.
     */
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'member_positions')
            ->withPivot(['date_assigned', 'is_current'])
            ->withTimestamps();
    }

    /**
     * Get the member position records.
     */
    public function memberPositions(): HasMany
    {
        return $this->hasMany(MemberPosition::class);
    }

    /**
     * Get the emergency contacts for the member.
     */
    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    /**
     * Get the attendance records for the member.
     */
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get attendance records recorded by the member.
     */
    public function recordedAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'recorded_by');
    }

    /**
     * Get weekday schedules assigned to the member.
     */
    public function weekdaySchedules(): BelongsToMany
    {
        return $this->belongsToMany(ScheduleWeekday::class, 'weekday_schedule_members')
            ->withPivot(['is_active', 'assigned_date'])
            ->withTimestamps();
    }

    /**
     * Get Sunday schedules assigned to the member.
     */
    public function sundaySchedules(): BelongsToMany
    {
        return $this->belongsToMany(ScheduleSunday::class, 'sunday_schedule_members')
            ->withPivot(['is_active', 'assigned_date'])
            ->withTimestamps();
    }

    /**
     * Get the user log entries for the member.
     */
    public function userLogs(): HasMany
    {
        return $this->hasMany(UserLog::class);
    }

    /**
     * Scope only active members.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope only inactive members.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Scope only alumni members.
     */
    public function scopeAlumni(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ALUMNI);
    }

    /**
     * Search members by name, username, email, or GKK.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search !== null && $search !== '', function (Builder $builder) use ($search): void {
            $builder->where(function (Builder $nested) use ($search): void {
                $nested->where('username', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('middle_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email_address', 'like', '%' . $search . '%')
                    ->orWhere('gkk', 'like', '%' . $search . '%');
            });
        });
    }

    /**
     * Check if the user has access to a specific permission area.
     */
    public function canAccess(string $area): bool
    {
        // Get active positions
        $activePositions = $this->positions()
            ->wherePivot('is_current', true)
            ->pluck('position_name')
            ->toArray();

        // If no active positions, default to nothing
        if (empty($activePositions)) {
            return false;
        }

        // Admin/Coordinator/Vice Coordinator/Arts Chairman can see all
        $superRoles = ['Coordinator', 'Vice Coordinator', 'Arts Chairman'];
        if (array_intersect($superRoles, $activePositions)) {
            return true;
        }

        // Define permissions per position
        $permissions = [
            'Secretary' => ['dashboard', 'schedules', 'schedules.create', 'members', 'attendance', 'notifications', 'settings'],
            'Assistant Secretary' => ['dashboard', 'schedules', 'schedules.create', 'members', 'attendance', 'notifications', 'settings'],
            
            'Treasurer' => ['dashboard', 'schedules', 'notifications', 'settings'],
            'Assistant Treasurer' => ['dashboard', 'schedules', 'notifications', 'settings'],
            
            'Socio-Cultural Chairman' => ['dashboard', 'schedules', 'notifications', 'settings'],
            
            'Spirituality Chairman' => ['schedules', 'notifications', 'settings'],
            'Sports Chairman' => ['schedules', 'notifications', 'settings'],
            'Music Chairman' => ['schedules', 'notifications', 'settings'],
            
            'Companion Brother' => ['schedules', 'notifications', 'settings'],
            
            'Member' => ['member-dashboard', 'settings']
        ];

        // Gather all permissions for all active roles the user has
        $allowedAreas = [];
        foreach ($activePositions as $pos) {
            if (isset($permissions[$pos])) {
                $allowedAreas = array_merge($allowedAreas, $permissions[$pos]);
            }
        }

        return in_array($area, $allowedAreas);
    }
}