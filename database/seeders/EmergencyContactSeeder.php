<?php

namespace Database\Seeders;

use App\Models\EmergencyContact;
use App\Models\Member;
use Illuminate\Database\Seeder;

class EmergencyContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::query()->orderBy('id')->chunk(20, function ($members): void {
            foreach ($members as $member) {
                EmergencyContact::factory()->create([
                    'member_id' => $member->id,
                    'contact_name' => fake()->name(fake()->randomElement(['male', 'female'])),
                    'relationship' => fake()->randomElement(['Mother', 'Father', 'Guardian', 'Brother', 'Sister']),
                    'address' => fake()->streetAddress() . ', ' . fake()->city() . ', Philippines',
                    'contact_number' => fake()->numerify('09#########'),
                ]);
            }
        });
    }
}