<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\MemberPosition;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MemberPosition>
 */
class MemberPositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'position_id' => Position::factory(),
            'date_assigned' => fake()->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
            'is_current' => true,
        ];
    }
}