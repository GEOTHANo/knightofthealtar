<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Attendance;
use App\Models\MassWeekday;
use App\Models\MassSunday;
use App\Models\ScheduleWeekday;
use App\Models\ScheduleSunday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        // Get all schedules grouped by week_start, ordered by newest first
        $weekdaySchedules = ScheduleWeekday::with('members')
            ->orderBy('week_start', 'desc')
            ->get()
            ->groupBy('week_start');

        $sundaySchedules = ScheduleSunday::with('members')
            ->orderBy('mass_date', 'desc')
            ->get()
            ->groupBy(function ($item) {
                // Group by the week that contains this sunday
                return Carbon::parse($item->mass_date)->startOfWeek(Carbon::SATURDAY)->subWeek()->format('Y-m-d');
            });

        // Combine into weeks
        $allWeeks = collect();
        foreach ($weekdaySchedules as $weekStart => $schedules) {
            $allWeeks[$weekStart] = [
                'weekday' => $schedules,
                'sunday' => $sundaySchedules[$weekStart] ?? collect(),
                'status' => $schedules->first()->status ?? 'not checked',
            ];
        }

        return view('schedules.index', compact('allWeeks'));
    }

    public function create()
    {
        $weekdayMasses = MassWeekday::active()->orderBy('day')->orderBy('mass_time')->get();
        $sundayMasses = MassSunday::active()->orderBy('mass_time')->get();

        // Get active members who are present or excused in current week attendance
        $weekStart = Carbon::now()->previous(Carbon::SATURDAY);
        $weekEnd = $weekStart->copy()->addDays(6);

        $eligibleMembers = Member::active()
            ->where('is_deleted', false)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('schedules.create', compact('weekdayMasses', 'sundayMasses', 'eligibleMembers', 'weekStart', 'weekEnd'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'week_start' => 'required|date',
            'week_end' => 'required|date',
        ]);

        $weekStart = $request->week_start;
        $weekEnd = $request->week_end;

        DB::transaction(function () use ($request, $weekStart, $weekEnd) {
            // Create weekday schedules
            if ($request->has('weekday_masses')) {
                foreach ($request->weekday_masses as $massId => $data) {
                    $mass = MassWeekday::findOrFail($massId);

                    $schedule = ScheduleWeekday::create([
                        'week_start' => $weekStart,
                        'week_end' => $weekEnd,
                        'day' => $mass->day,
                        'mass_type' => $mass->mass_type,
                        'mass_time' => $mass->mass_time,
                        'status' => 'not checked',
                    ]);

                    if (isset($data['members'])) {
                        foreach ($data['members'] as $memberId) {
                            $schedule->members()->attach($memberId, [
                                'is_active' => true,
                                'assigned_date' => now()->toDateString(),
                            ]);
                        }
                    }
                }
            }

            // Create sunday schedules
            if ($request->has('sunday_masses')) {
                foreach ($request->sunday_masses as $massId => $data) {
                    $mass = MassSunday::findOrFail($massId);

                    // Determine the sunday within the week
                    $sundayDate = Carbon::parse($weekStart)->next(Carbon::SUNDAY);

                    $schedule = ScheduleSunday::updateOrCreate(
                        ['mass_date' => $sundayDate->toDateString()],
                        [
                            'mass_name' => $mass->mass_name,
                            'mass_time' => $mass->mass_time,
                            'status' => 'not checked',
                        ]
                    );

                    if (isset($data['members'])) {
                        foreach ($data['members'] as $memberId) {
                            $schedule->members()->attach($memberId, [
                                'is_active' => true,
                                'assigned_date' => now()->toDateString(),
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function show($weekStart)
    {
        $weekdaySchedules = ScheduleWeekday::with('members')
            ->where('week_start', $weekStart)
            ->orderBy('day')
            ->orderBy('mass_time')
            ->get();

        $sundaySchedules = ScheduleSunday::with('members')
            ->whereBetween('mass_date', [$weekStart, Carbon::parse($weekStart)->addDays(8)])
            ->get();

        $isChecked = $weekdaySchedules->first()?->status === 'checked';

        return view('schedules.show', compact('weekdaySchedules', 'sundaySchedules', 'weekStart', 'isChecked'));
    }

    public function check()
    {
        // Get all schedules with status 'not checked'
        $weekdaySchedules = ScheduleWeekday::with('members')
            ->where('status', 'not checked')
            ->orderBy('week_start', 'desc')
            ->get()
            ->groupBy('week_start');

        $sundaySchedules = ScheduleSunday::with('members')
            ->where('status', 'not checked')
            ->orderBy('mass_date', 'desc')
            ->get();

        return view('schedules.check', compact('weekdaySchedules', 'sundaySchedules'));
    }

    public function submitCheck(Request $request)
    {
        DB::transaction(function () use ($request) {
            // Update weekday schedule member attendance
            if ($request->has('weekday_attendance')) {
                foreach ($request->weekday_attendance as $scheduleId => $members) {
                    $schedule = ScheduleWeekday::findOrFail($scheduleId);
                    foreach ($members as $memberId => $status) {
                        $schedule->members()->updateExistingPivot($memberId, [
                            'attendance_status' => $status,
                        ]);
                    }
                    $schedule->update(['status' => 'checked']);
                }
            }

            // Update sunday schedule member attendance
            if ($request->has('sunday_attendance')) {
                foreach ($request->sunday_attendance as $scheduleId => $members) {
                    $schedule = ScheduleSunday::findOrFail($scheduleId);
                    foreach ($members as $memberId => $status) {
                        $schedule->members()->updateExistingPivot($memberId, [
                            'attendance_status' => $status,
                        ]);
                    }
                    $schedule->update(['status' => 'checked']);
                }
            }
        });

        return redirect()->route('schedules.index')->with('success', 'Schedule checked and submitted successfully.');
    }
}
