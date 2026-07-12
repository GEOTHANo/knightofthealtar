<?php

namespace Database\Factories;

use App\Models\EmergencyContact;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmergencyContact>
 */
class EmergencyContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $relationships = ['Mother', 'Father', 'Guardian', 'Brother', 'Sister', 'Aunt', 'Uncle'];

        return [
            'member_id' => Member::factory(),
            'contact_name' => fake()->name(),
            'relationship' => fake()->randomElement($relationships),
            'address' => fake()->optional(0.7)->streetAddress() . ', ' . fake()->city() . ', Philippines',
            'contact_number' => fake()->numerify('09#########'),
        ];
    }
}