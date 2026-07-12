<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\UserLog;
use Illuminate\Database\Seeder;

class UserLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::query()->active()->inRandomOrder()->take(25)->get()->each(function (Member $member): void {
            UserLog::factory()->create([
                'member_id' => $member->id,
                'login_time' => fake()->dateTimeBetween('-30 days', 'now'),
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
            ]);
        });
    }
}