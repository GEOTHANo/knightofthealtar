@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center">
        <a href="{{ route('schedules.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 mr-3 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Check Pending Schedules</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Record attendance for members assigned to masses.</p>
        </div>
    </div>

    @if($weekdaySchedules->isEmpty() && $sundaySchedules->isEmpty())
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-12 text-center">
        <div class="bg-blue-50 dark:bg-blue-950/60 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-blue-200/50 dark:border-blue-900/40">
            <i data-lucide="check-circle" class="w-8 h-8 text-blue-600 dark:text-blue-400"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-1">All caught up!</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm">There are no pending schedules that need attendance checking.</p>
    </div>
    @else
    <form method="POST" action="{{ route('schedules.submitCheck') }}">
        @csrf

        @if($sundaySchedules->isNotEmpty())
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 mb-6">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Sunday Schedules (Pending)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sundaySchedules as $schedule)
                <div class="border border-amber-200/60 dark:border-amber-900/40 rounded-xl overflow-hidden bg-amber-50/20 dark:bg-amber-950/20">
                    <div class="bg-amber-100/60 dark:bg-amber-950/50 px-4 py-3 border-b border-amber-200/60 dark:border-amber-900/40">
                        <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ $schedule->mass_name }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($schedule->mass_date)->format('M d, Y') }} • {{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                @forelse($schedule->members as $member)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">{{ $member->last_name }}, {{ $member->first_name }}</td>
                                    <td class="px-4 py-2.5 text-right">
                                        <select name="sunday_attendance[{{ $schedule->id }}][{{ $member->id }}]" required class="text-xs px-2.5 py-1 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                                            <option value="Present">Present</option>
                                            <option value="Absent">Absent</option>
                                            <option value="Excused">Excused</option>
                                        </select>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="px-4 py-4 text-center text-slate-400 dark:text-slate-500 text-xs italic">No members assigned.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($weekdaySchedules->isNotEmpty())
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 mb-6">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Weekday Schedules (Pending)</h3>
            
            @foreach($weekdaySchedules as $weekStart => $schedules)
            <div class="mb-6 last:mb-0">
                <h4 class="text-xs font-bold text-slate-400 dark:text-slate-400 mb-3 uppercase tracking-wider">Week of {{ \Carbon\Carbon::parse($weekStart)->format('M d, Y') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($schedules as $schedule)
                    <div class="border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden bg-slate-50/50 dark:bg-slate-800/40">
                        <div class="bg-slate-100/80 dark:bg-slate-800 px-4 py-3 border-b border-slate-200/60 dark:border-slate-700/60">
                            <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ $schedule->day }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $schedule->mass_type }} • {{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                        </div>
                        <div class="p-0">
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                    @forelse($schedule->members as $member)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                        <td class="px-4 py-2.5 text-slate-800 dark:text-slate-200">{{ $member->last_name }}, {{ $member->first_name }}</td>
                                        <td class="px-4 py-2.5 text-right">
                                            <select name="weekday_attendance[{{ $schedule->id }}][{{ $member->id }}]" required class="text-xs px-2.5 py-1 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                                                <option value="Present">Present</option>
                                                <option value="Absent">Absent</option>
                                                <option value="Excused">Excused</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="2" class="px-4 py-4 text-center text-slate-400 dark:text-slate-500 text-xs italic">No members assigned.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('schedules.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Cancel</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-xs flex items-center">
                <i data-lucide="check" class="w-4 h-4 mr-2"></i> Submit Attendance
            </button>
        </div>
    </form>
    @endif
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
