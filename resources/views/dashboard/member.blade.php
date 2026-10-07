@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Member Dashboard</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Hello, {{ auth()->user()->first_name }}! Here is your schedule and info for this month.</p>
    </div>

    <!-- 3 Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Sunday Schedule Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 flex flex-col min-h-[400px]">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-50 dark:bg-blue-950/60 p-2.5 rounded-xl text-blue-600 dark:text-blue-400 border border-blue-100/50 dark:border-blue-900/40">
                        <i data-lucide="calendar-days" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-slate-200">Sunday Schedule</h3>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto space-y-3 pr-1 max-h-[320px]">
                @forelse($sundaySchedules as $schedule)
                <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl flex items-center justify-between border border-slate-200/60 dark:border-slate-700/60 hover:bg-slate-100/80 dark:hover:bg-slate-800 transition-colors">
                    <div>
                        <p class="font-semibold text-sm text-slate-800 dark:text-slate-200">{{ $schedule->mass_name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ $schedule->mass_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($schedule->mass_time)->format('h:i A') }}
                        </p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full 
                        @if($schedule->pivot->attendance_status == 'Present') bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 border border-blue-200/50 dark:border-blue-800/40
                        @elseif($schedule->pivot->attendance_status == 'Absent') bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300 border border-rose-200/50 dark:border-rose-800/40
                        @else bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-200/50 dark:border-amber-800/40
                        @endif">
                        {{ $schedule->pivot->attendance_status ?? 'Pending' }}
                    </span>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center py-12 text-center">
                    <i data-lucide="sparkles" class="w-8 h-8 text-slate-300 dark:text-slate-600 mb-2"></i>
                    <p class="text-slate-400 dark:text-slate-500 text-sm">No upcoming Sunday masses assigned.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Weekdays Schedule Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 flex flex-col min-h-[400px]">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="bg-indigo-50 dark:bg-indigo-950/60 p-2.5 rounded-xl text-indigo-600 dark:text-indigo-400 border border-indigo-100/50 dark:border-indigo-900/40">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-slate-200">Weekdays</h3>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto space-y-3 pr-1 max-h-[320px]">
                @forelse($weekdaySchedules as $schedule)
                <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl flex items-center justify-between border border-slate-200/60 dark:border-slate-700/60 hover:bg-slate-100/80 dark:hover:bg-slate-800 transition-colors">
                    <div>
                        <p class="font-semibold text-sm text-slate-800 dark:text-slate-200">
                            {{ $schedule->day->value ?? $schedule->day }} - {{ $schedule->mass_type->value ?? $schedule->mass_type }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($schedule->mass_time)->format('h:i A') }}
                        </p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full 
                        @if($schedule->pivot->attendance_status == 'Present') bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 border border-blue-200/50 dark:border-blue-800/40
                        @elseif($schedule->pivot->attendance_status == 'Absent') bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300 border border-rose-200/50 dark:border-rose-800/40
                        @else bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-200/50 dark:border-amber-800/40
                        @endif">
                        {{ $schedule->pivot->attendance_status ?? 'Pending' }}
                    </span>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center py-12 text-center">
                    <i data-lucide="coffee" class="w-8 h-8 text-slate-300 dark:text-slate-600 mb-2"></i>
                    <p class="text-slate-400 dark:text-slate-500 text-sm">No weekday schedules assigned for this week.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Birthdays Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 flex flex-col min-h-[400px]">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="bg-rose-50 dark:bg-rose-950/60 p-2.5 rounded-xl text-rose-600 dark:text-rose-400 border border-rose-100/50 dark:border-rose-900/40">
                        <i data-lucide="cake" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-slate-200">Birthdays This Month</h3>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto space-y-3 pr-1 max-h-[320px]">
                @forelse($birthdaysThisMonth as $birthdayMember)
                <div class="flex items-center justify-between p-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-xl transition-colors">
                    <div class="flex items-center">
                        <div class="bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3 text-xs border border-rose-200/50 dark:border-rose-800/40">
                            {{ strtoupper(substr($birthdayMember->first_name, 0, 1) . substr($birthdayMember->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-slate-800 dark:text-slate-200 {{ $birthdayMember->id === auth()->id() ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                {{ $birthdayMember->first_name }} {{ $birthdayMember->last_name }}
                                @if($birthdayMember->id === auth()->id())
                                    <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">(You)</span>
                                @endif
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($birthdayMember->birth_date)->format('M d') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @php
                            $daysLeft = \Carbon\Carbon::parse($birthdayMember->birth_date)->day - now()->day;
                        @endphp
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $daysLeft == 0 ? 'bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300' : ($daysLeft > 0 ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400') }}">
                            @if($daysLeft == 0)
                                Today!
                            @elseif($daysLeft > 0)
                                In {{ $daysLeft }}d
                            @else
                                Celebrated
                            @endif
                        </span>
                    </div>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center py-12 text-center">
                    <i data-lucide="calendar-heart" class="w-8 h-8 text-slate-300 dark:text-slate-600 mb-2"></i>
                    <p class="text-slate-400 dark:text-slate-500 text-sm">No member birthdays this month.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection
