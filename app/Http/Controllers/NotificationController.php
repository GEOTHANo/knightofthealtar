<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Position;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $userRoleIds = auth()->user()->positions()->where('is_current', true)->pluck('positions.id')->toArray();
        $activeRoles = auth()->user()->positions()->where('is_current', true)->pluck('position_name')->toArray();
        
        $canCreate = (bool) array_intersect(['Coordinator', 'Vice Coordinator', 'Secretary', 'Arts Chairman'], $activeRoles);

        $notifications = Notification::with('creator')
            ->where(function($q) use ($userRoleIds) {
                // User can see notifications they created
                $q->where('created_by', auth()->id());
                // And notifications targeted at their active roles
                foreach ($userRoleIds as $id) {
                    $q->orWhereJsonContains('target_roles', (int)$id)
                      ->orWhereJsonContains('target_roles', (string)$id);
                }
            })
            ->latest()
            ->paginate(15);
            
        $positions = Position::all();
        return view('notifications.index', compact('notifications', 'positions', 'canCreate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_roles' => 'required|array',
            'target_roles.*' => 'exists:positions,id',
        ]);

        Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'target_roles' => $request->target_roles,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('notifications.index')->with('success', 'Notification created successfully.');
    }
}
