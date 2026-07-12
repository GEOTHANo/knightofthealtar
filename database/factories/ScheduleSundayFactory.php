<?php

namespace Database\Factories;

use App\Models\ScheduleSunday;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ScheduleSunday>
 */
class ScheduleSundayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mass_date' => Carbon::now()->next(Carbon::SUNDAY)->toDateString(),
            'mass_name' => 'Sunday Mass',
            'mass_time' => fake()->randomElement(['07:00:00', '08:30:00', '10:00:00', '18:00:00']),
            'notes' => fake()->optional(0.5)->sentence(),
        ];
    }
}