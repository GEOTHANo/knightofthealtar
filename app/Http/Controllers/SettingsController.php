<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $member = Auth::user();
        $member->load(['positions' => function ($q) {
            $q->where('is_current', true);
        }]);

        return view('settings.index', compact('member'));
    }

    public function update(Request $request)
    {
        $member = Auth::user();

        $validated = $request->validate([
            'username' => 'required|unique:members,username,' . $member->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        $member->username = $validated['username'];

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $member->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $member->password = $request->new_password;
        }

        $member->save();

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
