<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\ScheduleSunday;
use App\Models\SundayScheduleMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SundayScheduleMember>
 */
class SundayScheduleMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sunday_schedule_id' => ScheduleSunday::factory(),
            'member_id' => Member::factory(),
            'is_active' => true,
            'assigned_date' => now()->toDateString(),
        ];
    }
}