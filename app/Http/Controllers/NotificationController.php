<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Position;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('creator')->latest()->get();
        $positions = Position::all();
        return view('notifications.index', compact('notifications', 'positions'));
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
