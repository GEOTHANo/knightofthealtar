<?php

namespace Database\Seeders;

use App\Enums\DayOfWeek;
use App\Enums\MassType;
use App\Models\Member;
use App\Models\ScheduleSunday;
use App\Models\ScheduleWeekday;
use App\Models\SundayScheduleMember;
use App\Models\WeekdayScheduleMember;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY);
        $activeMemberIds = Member::query()->active()->pluck('id');

        foreach (DayOfWeek::cases() as $day) {
            foreach (MassType::cases() as $massType) {
                $schedule = ScheduleWeekday::query()->create([
                    'week_start' => $weekStart->toDateString(),
                    'week_end' => $weekEnd->toDateString(),
                    'day' => $day->value,
                    'mass_type' => $massType->value,
                    'mass_time' => $massType === MassType::AM ? '06:00:00' : '17:30:00',
                ]);

                $memberIds = $activeMemberIds->shuffle()->take(4);

                foreach ($memberIds as $memberId) {
                    WeekdayScheduleMember::query()->create([
                        'weekday_schedule_id' => $schedule->id,
                        'member_id' => $memberId,
                        'is_active' => true,
                        'assigned_date' => $weekStart->toDateString(),
                    ]);
                }
            }
        }

        $nextSunday = Carbon::now()->next(Carbon::SUNDAY);

        for ($offset = 0; $offset < 4; $offset++) {
            $massDate = $nextSunday->copy()->addWeeks($offset);

            $sundaySchedule = ScheduleSunday::query()->create([
                'mass_date' => $massDate->toDateString(),
                'mass_name' => 'Sunday Mass',
                'mass_time' => fake()->randomElement(['07:00:00', '08:30:00', '10:00:00', '18:00:00']),
                'notes' => fake()->optional(0.4)->sentence(),
            ]);

            $memberIds = $activeMemberIds->shuffle()->take(6);

            foreach ($memberIds as $memberId) {
                SundayScheduleMember::query()->create([
                    'sunday_schedule_id' => $sundaySchedule->id,
                    'member_id' => $memberId,
                    'is_active' => true,
                    'assigned_date' => $massDate->toDateString(),
                ]);
            }
        }
    }
}