<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        // Current week Saturday to Friday
        $weekStart = Carbon::now()->previous(Carbon::SATURDAY);
        $weekEnd = $weekStart->copy()->addDays(6);

        $activeMembers = Member::active()->orderBy('last_name')->orderBy('first_name')->get();

        // Get attendance for current week
        $attendanceRecords = Attendance::whereBetween('meeting_week_start', [$weekStart->subWeeks(4), $weekEnd])
            ->get()
            ->groupBy(function ($record) {
                return $record->meeting_week_start->format('Y-m-d');
            });

        // Current week stats
        $currentWeekAttendance = Attendance::where('meeting_week_start', $weekStart->copy()->addWeeks(4))
            ->get();

        $totalPresent = $currentWeekAttendance->where('status', 'Present')->count();
        $totalAbsent = $currentWeekAttendance->where('status', 'Absent')->count();
        $totalExcused = $currentWeekAttendance->where('status', 'Excused')->count();

        return view('attendance.index', compact(
            'activeMembers', 'attendanceRecords', 'weekStart', 'weekEnd',
            'totalPresent', 'totalAbsent', 'totalExcused'
        ));
    }

    public function check()
    {
        $activeMembers = Member::active()->orderBy('last_name')->orderBy('first_name')->get();

        // Default week: Saturday to Friday
        $weekStart = Carbon::now()->previous(Carbon::SATURDAY);
        $weekEnd = $weekStart->copy()->addDays(6);

        return view('attendance.check', compact('activeMembers', 'weekStart', 'weekEnd'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'week_start' => 'required|date',
            'week_end' => 'required|date|after_or_equal:week_start',
            'attendance' => 'required|array',
            'attendance.*.member_id' => 'required|exists:members,id',
            'attendance.*.status' => 'required|in:Present,Absent,Excused',
        ]);

        $weekStart = $request->week_start;
        $weekEnd = $request->week_end;

        foreach ($request->attendance as $record) {
            Attendance::updateOrCreate(
                [
                    'member_id' => $record['member_id'],
                    'meeting_week_start' => $weekStart,
                    'attendance_date' => now()->toDateString(),
                ],
                [
                    'meeting_week_end' => $weekEnd,
                    'status' => $record['status'],
                    'recorded_by' => auth()->id(),
                    'date_recorded' => now(),
                ]
            );
        }

        return redirect()->route('attendance.index')->with('success', 'Attendance saved successfully.');
    }
}
