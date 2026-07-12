<?php

namespace Database\Factories;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY);

        return [
            'member_id' => Member::factory(),
            'meeting_week_start' => $weekStart->toDateString(),
            'meeting_week_end' => $weekEnd->toDateString(),
            'attendance_date' => $weekStart->copy()->addDays(fake()->numberBetween(0, 5))->toDateString(),
            'status' => fake()->randomElement(AttendanceStatus::cases())->value,
            'remarks' => fake()->optional(0.4)->sentence(),
            'recorded_by' => Member::factory(),
            'date_recorded' => now()->subMinutes(fake()->numberBetween(1, 240)),
        ];
    }
}