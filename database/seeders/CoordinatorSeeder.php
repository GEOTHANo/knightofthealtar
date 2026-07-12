<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CoordinatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure Coordinator position exists
        $coordinatorPosition = Position::firstOrCreate(
            ['position_name' => 'Coordinator']
        );

        // Create the Coordinator Member
        $coordinator = Member::firstOrCreate(
            ['username' => 'coordinator'],
            [
                'password' => 'password', // will be hashed by model
                'first_name' => 'Admin',
                'last_name' => 'Coordinator',
                'birth_date' => '1980-01-01',
                'status' => 'Active',
            ]
        );

        // Assign position
        if (!$coordinator->positions()->where('position_id', $coordinatorPosition->id)->exists()) {
            $coordinator->positions()->attach($coordinatorPosition->id, [
                'date_assigned' => now()->toDateString(),
                'is_current' => true,
            ]);
        }
        
        // Also ensure Member position exists just in case
        Position::firstOrCreate(
            ['position_name' => 'Member']
        );
        
        Position::firstOrCreate(
            ['position_name' => 'Companion Brother']
        );
    }
}
