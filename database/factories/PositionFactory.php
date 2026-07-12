<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
{
    /**
     * Known Knights of the Altar positions.
     *
     * @var array<int, string>
     */
    private const POSITIONS = [
        'Coordinator',
        'Vice Coordinator',
        'Secretary',
        'Assistant Secretary',
        'Treasurer',
        'Assistant Treasurer',
        'Socio-Cultural Chairman',
        'Spirituality Chairman',
        'Sports Chairman',
        'Music Chairman',
        'Arts Chairman',
        'Companion Brother',
        'Member',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'position_name' => fake()->unique()->randomElement(self::POSITIONS),
        ];
    }
}