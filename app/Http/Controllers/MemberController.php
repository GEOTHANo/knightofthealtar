<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Position;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with(['positions' => function ($q) {
                $q->where('is_current', true);
            }])
            ->where('is_deleted', false)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('members.index', compact('members'));
    }

    public function create()
    {
        $positions = Position::ordered()->get();
        return view('members.create', compact('positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:Male,Female,Prefer not to say',
            'complete_address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:25',
            'email_address' => 'nullable|email|unique:members,email_address',
            'school_attended' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'number_of_siblings' => 'nullable|integer|min:0',
            'gkk' => 'nullable|string|max:255',
            'date_of_acceptance' => 'nullable|date',
            'batch_year' => 'nullable|integer',
            'position_ids' => 'nullable|array',
            'position_ids.*' => 'exists:positions,id',
        ]);

        // Auto-generate username (tol + clean lastname)
        $cleanLastName = strtolower(str_replace(' ', '', $validated['last_name']));
        $baseUsername = 'tol' . $cleanLastName;
        $username = $baseUsername;
        $counter = 1;

        while (Member::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $validated['username'] = $username;
        $validated['password'] = $username; // Hashed automatically by the model setter

        $member = Member::create($validated);

        if ($request->has('position_ids')) {
            foreach ($request->position_ids as $positionId) {
                $member->positions()->attach($positionId, [
                    'date_assigned' => now()->toDateString(),
                    'is_current' => true,
                ]);
            }
        }

        return redirect()->route('members.index')->with('success', 'Member added successfully.');
    }

    public function show(Member $member)
    {
        $member->load(['positions' => function ($q) {
            $q->where('is_current', true);
        }]);
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $positions = Position::ordered()->get();
        $member->load(['positions' => function ($q) {
            $q->where('is_current', true);
        }]);
        return view('members.edit', compact('member', 'positions'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'username' => 'required|unique:members,username,' . $member->id,
            'password' => 'nullable|min:6',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:Male,Female,Prefer not to say',
            'complete_address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:25',
            'email_address' => 'nullable|email|unique:members,email_address,' . $member->id,
            'school_attended' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'number_of_siblings' => 'nullable|integer|min:0',
            'gkk' => 'nullable|string|max:255',
            'date_of_acceptance' => 'nullable|date',
            'batch_year' => 'nullable|integer',
            'position_ids' => 'nullable|array',
            'position_ids.*' => 'exists:positions,id',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $member->update($validated);

        // Sync positions
        if ($request->has('position_ids')) {
            // Mark old positions as not current
            $member->positions()->updateExistingPivot(
                $member->positions()->pluck('positions.id')->toArray(),
                ['is_current' => false]
            );
            // Attach new positions
            foreach ($request->position_ids as $positionId) {
                $member->positions()->syncWithoutDetaching([
                    $positionId => [
                        'date_assigned' => now()->toDateString(),
                        'is_current' => true,
                    ]
                ]);
            }
        }

        return redirect()->route('members.show', $member)->with('success', 'Member updated successfully.');
    }

    public function archive(Member $member)
    {
        $member->update(['status' => 'Inactive']);
        return redirect()->route('members.index')->with('success', 'Member archived successfully.');
    }
}
