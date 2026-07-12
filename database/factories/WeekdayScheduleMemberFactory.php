<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\ScheduleWeekday;
use App\Models\WeekdayScheduleMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WeekdayScheduleMember>
 */
class WeekdayScheduleMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'weekday_schedule_id' => ScheduleWeekday::factory(),
            'member_id' => Member::factory(),
            'is_active' => true,
            'assigned_date' => now()->toDateString(),
        ];
    }
}