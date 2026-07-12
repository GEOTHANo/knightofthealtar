<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberPosition;
use App\Models\Position;
use Illuminate\Database\Seeder;

class MemberPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = Member::query()->orderBy('id')->get();
        $positions = Position::query()->get()->keyBy('position_name');
        $leadershipAssignments = [
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

        foreach ($members as $index => $member) {
            $positionName = $index < count($leadershipAssignments) ? $leadershipAssignments[$index] : 'Member';
            $position = $positions->get($positionName);

            if ($position === null) {
                continue;
            }

            MemberPosition::query()->updateOrCreate([
                'member_id' => $member->id,
                'position_id' => $position->id,
                'is_current' => true,
            ], [
                'date_assigned' => $member->created_at?->toDateString() ?? now()->toDateString(),
            ]);
        }
    }
}