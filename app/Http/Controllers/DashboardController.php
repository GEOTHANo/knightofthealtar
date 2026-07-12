<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // If they can't access dashboard at all, but can access schedules, redirect them to schedules
        if (!$user->canAccess('dashboard') && $user->canAccess('schedules')) {
            return redirect()->route('schedules.index');
        }

        // If they are a member (only has member-dashboard and settings), show member dashboard
        if ($user->canAccess('member-dashboard') && !$user->canAccess('dashboard')) {
            $sundaySchedules = $user->sundaySchedules()
                ->whereDate('mass_date', '>=', now()->toDateString())
                ->orderBy('mass_date')
                ->get();
            $weekdaySchedules = $user->weekdaySchedules()
                ->whereDate('week_end', '>=', now()->toDateString())
                ->get();
            $birthdaysThisMonth = Member::whereMonth('birth_date', now()->month)
                ->orderByRaw('DAY(birth_date) ASC')
                ->get();

            return view('dashboard.member', compact('sundaySchedules', 'weekdaySchedules', 'birthdaysThisMonth'));
        }

        $totalMembers = Member::count();
        $activeMembers = Member::active()->count();
        $inactiveMembers = Member::inactive()->count();
        $alumniMembers = Member::alumni()->count();
        $birthdaysThisMonth = Member::whereMonth('birth_date', now()->month)
            ->orderByRaw('DAY(birth_date) ASC')
            ->get();

        // Current week (Saturday to Friday)
        $weekStart = Carbon::now()->startOfWeek(Carbon::SATURDAY)->subWeek();
        $weekEnd = $weekStart->copy()->addDays(6);

        $presentThisWeek = Attendance::whereBetween('attendance_date', [$weekStart, $weekEnd])
            ->where('status', 'Present')
            ->distinct('member_id')
            ->count('member_id');

        $absentThisWeek = Attendance::whereBetween('attendance_date', [$weekStart, $weekEnd])
            ->where('status', 'Absent')
            ->distinct('member_id')
            ->count('member_id');

        // Non-member positions (coordinators, companion brothers, etc.)
        $leaders = Member::active()
            ->whereHas('positions', function ($q) {
                $q->where('position_name', '!=', 'Member')->where('is_current', true);
            })
            ->with(['positions' => function ($q) {
                $q->where('is_current', true);
            }])
            ->orderBy('last_name')
            ->get();

        // Monthly attendance data for last 12 months
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Attendance::whereYear('attendance_date', $month->year)
                ->whereMonth('attendance_date', $month->month)
                ->where('status', 'Present')
                ->count();
            $monthlyData[] = [
                'label' => $month->format('M'),
                'count' => $count,
            ];
        }

        // Status distribution percentages
        $activePercent = $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100) : 0;
        $inactivePercent = $totalMembers > 0 ? round(($inactiveMembers / $totalMembers) * 100) : 0;
        $alumniPercent = $totalMembers > 0 ? round(($alumniMembers / $totalMembers) * 100) : 0;

        return view('dashboard', compact(
            'totalMembers', 'activeMembers', 'inactiveMembers', 'alumniMembers',
            'presentThisWeek', 'absentThisWeek',
            'leaders', 'monthlyData',
            'activePercent', 'inactivePercent', 'alumniPercent',
            'birthdaysThisMonth'
        ));
    }
}
