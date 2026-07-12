<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
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

        foreach ($positions as $positionName) {
            Position::query()->firstOrCreate([
                'position_name' => $positionName,
            ]);
        }
    }
}