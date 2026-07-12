<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\UserLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserLog>
 */
class UserLogFactory extends Factory
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
            'login_time' => fake()->dateTimeBetween('-30 days', 'now'),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}