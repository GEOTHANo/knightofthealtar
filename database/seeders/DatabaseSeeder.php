<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CoordinatorSeeder::class,
            PositionSeeder::class,
            // MemberSeeder::class,
            // MemberPositionSeeder::class,
            // EmergencyContactSeeder::class,
            // ScheduleSeeder::class,
            // AttendanceSeeder::class,
            // UserLogSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
