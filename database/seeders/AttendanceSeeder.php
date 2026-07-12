<?php

namespace Database\Seeders;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\ScheduleSunday;
use App\Models\ScheduleWeekday;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recorderId = Member::query()->active()->orderBy('id')->value('id');

        ScheduleWeekday::query()->with('members')->get()->each(function (ScheduleWeekday $schedule) use ($recorderId): void {
            $dayOffset = match ($schedule->day->value) {
                'Monday' => 0,
                'Tuesday' => 1,
                'Wednesday' => 2,
                'Thursday' => 3,
                'Friday' => 4,
                'Saturday' => 5,
            };

            $attendanceDate = Carbon::parse($schedule->week_start)->addDays($dayOffset)->toDateString();

            $schedule->members->each(function (Member $member) use ($attendanceDate, $schedule, $recorderId): void {
                Attendance::query()->create([
                    'member_id' => $member->id,
                    'meeting_week_start' => Carbon::parse($schedule->week_start)->toDateString(),
                    'meeting_week_end' => Carbon::parse($schedule->week_end)->toDateString(),
                    'attendance_date' => $attendanceDate,
                    'status' => fake()->randomElement([
                        AttendanceStatus::Present->value,
                        AttendanceStatus::Present->value,
                        AttendanceStatus::Present->value,
                        AttendanceStatus::Late->value,
                        AttendanceStatus::Absent->value,
                        AttendanceStatus::Excused->value,
                    ]),
                    'remarks' => fake()->optional(0.3)->sentence(),
                    'recorded_by' => $recorderId,
                    'date_recorded' => now()->subMinutes(fake()->numberBetween(5, 240)),
                ]);
            });
        });

        ScheduleSunday::query()->with('members')->get()->each(function (ScheduleSunday $schedule) use ($recorderId): void {
            $schedule->members->each(function (Member $member) use ($schedule, $recorderId): void {
                Attendance::query()->create([
                    'member_id' => $member->id,
                    'meeting_week_start' => Carbon::parse($schedule->mass_date)->startOfWeek(Carbon::MONDAY)->toDateString(),
                    'meeting_week_end' => Carbon::parse($schedule->mass_date)->endOfWeek(Carbon::SATURDAY)->toDateString(),
                    'attendance_date' => Carbon::parse($schedule->mass_date)->toDateString(),
                    'status' => fake()->randomElement([
                        AttendanceStatus::Present->value,
                        AttendanceStatus::Present->value,
                        AttendanceStatus::Late->value,
                        AttendanceStatus::Absent->value,
                        AttendanceStatus::Excused->value,
                    ]),
                    'remarks' => fake()->optional(0.2)->sentence(),
                    'recorded_by' => $recorderId,
                    'date_recorded' => now()->subMinutes(fake()->numberBetween(5, 240)),
                ]);
            });
        });
    }
}