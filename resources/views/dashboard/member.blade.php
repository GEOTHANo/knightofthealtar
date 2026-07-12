@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Member Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Hello, {{ auth()->user()->first_name }}! Here is your schedule and info for this month.</p>
    </div>

    <!-- 3 Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Sunday Schedule Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col min-h-[400px]">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-50">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-50 p-2 rounded-lg text-[#246b9c]">
                        <i data-lucide="calendar-days" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-gray-700">Sunday Schedule</h3>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto space-y-3 pr-1 max-h-[320px]">
                @forelse($sundaySchedules as $schedule)
                <div class="p-3 bg-gray-50 rounded-lg flex items-center justify-between border border-gray-100 hover:bg-gray-100/50 transition-colors">
                    <div>
                        <p class="font-semibold text-sm text-gray-800">{{ $schedule->mass_name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $schedule->mass_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($schedule->mass_time)->format('h:i A') }}
                        </p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full 
                        @if($schedule->pivot->attendance_status == 'Present') bg-green-100 text-green-800 
                        @elseif($schedule->pivot->attendance_status == 'Absent') bg-red-100 text-red-800 
                        @else bg-yellow-100 text-yellow-800 
                        @endif">
                        {{ $schedule->pivot->attendance_status ?? 'Pending' }}
                    </span>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center py-12 text-center">
                    <i data-lucide="sparkles" class="w-8 h-8 text-gray-300 mb-2"></i>
                    <p class="text-gray-400 text-sm">No upcoming Sunday masses assigned.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Weekdays Schedule Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col min-h-[400px]">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-50">
                <div class="flex items-center space-x-3">
                    <div class="bg-indigo-50 p-2 rounded-lg text-indigo-600">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-gray-700">Weekdays</h3>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto space-y-3 pr-1 max-h-[320px]">
                @forelse($weekdaySchedules as $schedule)
                <div class="p-3 bg-gray-50 rounded-lg flex items-center justify-between border border-gray-100 hover:bg-gray-100/50 transition-colors">
                    <div>
                        <p class="font-semibold text-sm text-gray-800">
                            {{ $schedule->day->value ?? $schedule->day }} - {{ $schedule->mass_type->value ?? $schedule->mass_type }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ \Carbon\Carbon::parse($schedule->mass_time)->format('h:i A') }}
                        </p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full 
                        @if($schedule->pivot->attendance_status == 'Present') bg-green-100 text-green-800 
                        @elseif($schedule->pivot->attendance_status == 'Absent') bg-red-100 text-red-800 
                        @else bg-yellow-100 text-yellow-800 
                        @endif">
                        {{ $schedule->pivot->attendance_status ?? 'Pending' }}
                    </span>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center py-12 text-center">
                    <i data-lucide="coffee" class="w-8 h-8 text-gray-300 mb-2"></i>
                    <p class="text-gray-400 text-sm">No weekday schedules assigned for this week.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Birthdays Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col min-h-[400px]">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-50">
                <div class="flex items-center space-x-3">
                    <div class="bg-rose-50 p-2 rounded-lg text-rose-600">
                        <i data-lucide="cake" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-gray-700">Birthdays This Month</h3>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto space-y-3 pr-1 max-h-[320px]">
                @forelse($birthdaysThisMonth as $birthdayMember)
                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="bg-rose-50 text-rose-600 font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3 text-xs">
                            {{ strtoupper(substr($birthdayMember->first_name, 0, 1) . substr($birthdayMember->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-800 {{ $birthdayMember->id === auth()->id() ? 'text-[#246b9c]' : '' }}">
                                {{ $birthdayMember->first_name }} {{ $birthdayMember->last_name }}
                                @if($birthdayMember->id === auth()->id())
                                    <span class="text-xs text-blue-600 font-normal">(You)</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($birthdayMember->birth_date)->format('M d') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @php
                            $daysLeft = \Carbon\Carbon::parse($birthdayMember->birth_date)->day - now()->day;
                        @endphp
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $daysLeft == 0 ? 'bg-green-100 text-green-800' : ($daysLeft > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
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
                    <i data-lucide="calendar-heart" class="w-8 h-8 text-gray-300 mb-2"></i>
                    <p class="text-gray-400 text-sm">No member birthdays this month.</p>
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
