@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route('schedules.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 mr-3 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Schedule Details</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Week of {{ \Carbon\Carbon::parse($weekStart)->format('M d, Y') }}</p>
            </div>
        </div>
        <div class="flex items-center self-start sm:self-auto">
            @if($isChecked)
                <span class="inline-flex items-center bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-sm px-3.5 py-1.5 rounded-full font-medium border border-blue-200/50 dark:border-blue-800/40">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-400"></i> Checked
                </span>
            @else
                <span class="inline-flex items-center bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-sm px-3.5 py-1.5 rounded-full font-medium border border-amber-200/50 dark:border-amber-800/40">
                    <i data-lucide="clock" class="w-4 h-4 mr-2 text-amber-600 dark:text-amber-400"></i> Not Checked
                </span>
                @if(auth()->user()->canAccess('schedules.create'))
                    <a href="{{ route('schedules.check') }}" class="ml-3 text-sm text-blue-600 dark:text-blue-400 hover:underline font-semibold">Check Now</a>
                @endif
            @endif
        </div>
    </div>

    <!-- Sunday Masses -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6">
        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Sunday Masses</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($sundaySchedules as $schedule)
            <div class="border border-amber-200/60 dark:border-amber-900/40 rounded-xl overflow-hidden bg-amber-50/20 dark:bg-amber-950/20">
                <div class="bg-amber-100/60 dark:bg-amber-950/50 px-4 py-3 border-b border-amber-200/60 dark:border-amber-900/40 flex items-center justify-between">
                    <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ $schedule->mass_name }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                </div>
                <div class="p-4">
                    <ul class="space-y-2">
                        @forelse($schedule->members as $member)
                        <li class="flex items-center justify-between text-sm py-1 border-b border-slate-100/50 dark:border-slate-800/40 last:border-0">
                            <span class="text-slate-700 dark:text-slate-300">{{ $member->last_name }}, {{ $member->first_name }}</span>
                            @if($isChecked)
                                @if($member->pivot->attendance_status === 'Present')
                                    <span class="text-xs text-blue-600 dark:text-blue-400 font-semibold">Present</span>
                                @elseif($member->pivot->attendance_status === 'Absent')
                                    <span class="text-xs text-rose-500 dark:text-rose-400 font-semibold">Absent</span>
                                @elseif($member->pivot->attendance_status === 'Excused')
                                    <span class="text-xs text-amber-600 dark:text-amber-400 font-semibold">Excused</span>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">—</span>
                                @endif
                            @endif
                        </li>
                        @empty
                        <li class="text-sm text-slate-400 dark:text-slate-500 italic">No members assigned.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @empty
            <p class="text-slate-400 dark:text-slate-500 text-sm py-4">No Sunday schedule for this week.</p>
            @endforelse
        </div>
    </div>

    <!-- Weekday Masses -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6">
        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Weekday Masses</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($weekdaySchedules as $schedule)
            <div class="border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden bg-slate-50/50 dark:bg-slate-800/40">
                <div class="bg-slate-100/80 dark:bg-slate-800 px-4 py-3 border-b border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between">
                    <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ $schedule->day }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $schedule->mass_type }} • {{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                </div>
                <div class="p-4">
                    <ul class="space-y-2">
                        @forelse($schedule->members as $member)
                        <li class="flex items-center justify-between text-sm py-1 border-b border-slate-100/50 dark:border-slate-800/40 last:border-0">
                            <span class="text-slate-700 dark:text-slate-300">{{ $member->last_name }}, {{ $member->first_name }}</span>
                            @if($isChecked)
                                @if($member->pivot->attendance_status === 'Present')
                                    <span class="text-xs text-blue-600 dark:text-blue-400 font-semibold">Present</span>
                                @elseif($member->pivot->attendance_status === 'Absent')
                                    <span class="text-xs text-rose-500 dark:text-rose-400 font-semibold">Absent</span>
                                @elseif($member->pivot->attendance_status === 'Excused')
                                    <span class="text-xs text-amber-600 dark:text-amber-400 font-semibold">Excused</span>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">—</span>
                                @endif
                            @endif
                        </li>
                        @empty
                        <li class="text-sm text-slate-400 dark:text-slate-500 italic">No members assigned.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @empty
            <p class="text-slate-400 dark:text-slate-500 text-sm py-4">No weekday schedule for this week.</p>
            @endforelse
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
