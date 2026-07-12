<?php

namespace Database\Factories;

use App\Enums\DayOfWeek;
use App\Enums\MassType;
use App\Models\ScheduleWeekday;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ScheduleWeekday>
 */
class ScheduleWeekdayFactory extends Factory
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
            'week_start' => $weekStart->toDateString(),
            'week_end' => $weekEnd->toDateString(),
            'day' => fake()->randomElement(DayOfWeek::cases())->value,
            'mass_type' => fake()->randomElement(MassType::cases())->value,
            'mass_time' => fake()->randomElement(['05:30:00', '06:30:00', '16:30:00', '18:00:00']),
        ];
    }
}