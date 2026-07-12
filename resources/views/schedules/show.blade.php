@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route('schedules.index') }}" class="text-gray-400 hover:text-gray-600 mr-3 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Schedule Details</h1>
                <p class="text-sm text-gray-500 mt-1">Week of {{ \Carbon\Carbon::parse($weekStart)->format('M d, Y') }}</p>
            </div>
        </div>
        <div class="flex items-center self-start sm:self-auto">
            @if($isChecked)
                <span class="inline-flex items-center bg-green-50 text-green-700 text-sm px-3 py-1.5 rounded-full font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Checked
                </span>
            @else
                <span class="inline-flex items-center bg-yellow-50 text-yellow-700 text-sm px-3 py-1.5 rounded-full font-medium">
                    <i data-lucide="clock" class="w-4 h-4 mr-2"></i> Not Checked
                </span>
                @if(auth()->user()->canAccess('schedules.create'))
                    <a href="{{ route('schedules.check') }}" class="ml-3 text-sm text-[#246b9c] hover:underline font-medium">Check Now</a>
                @endif
            @endif
        </div>
    </div>

    <!-- Sunday Masses -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Sunday Masses</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($sundaySchedules as $schedule)
            <div class="border border-yellow-100 rounded-lg overflow-hidden bg-yellow-50/10">
                <div class="bg-yellow-50 px-4 py-3 border-b border-yellow-100 flex items-center justify-between">
                    <div class="font-semibold text-gray-800">{{ $schedule->mass_name }}</div>
                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                </div>
                <div class="p-4">
                    <ul class="space-y-2">
                        @forelse($schedule->members as $member)
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-gray-700">{{ $member->last_name }}, {{ $member->first_name }}</span>
                            @if($isChecked)
                                @if($member->pivot->attendance_status === 'Present')
                                    <span class="text-xs text-green-600 font-medium">Present</span>
                                @elseif($member->pivot->attendance_status === 'Absent')
                                    <span class="text-xs text-red-500 font-medium">Absent</span>
                                @elseif($member->pivot->attendance_status === 'Excused')
                                    <span class="text-xs text-yellow-600 font-medium">Excused</span>
                                @else
                                    <span class="text-xs text-gray-400 font-medium">—</span>
                                @endif
                            @endif
                        </li>
                        @empty
                        <li class="text-sm text-gray-400 italic">No members assigned.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm py-4">No Sunday schedule for this week.</p>
            @endforelse
        </div>
    </div>

    <!-- Weekday Masses -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Weekday Masses</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($weekdaySchedules as $schedule)
            <div class="border border-gray-100 rounded-lg overflow-hidden bg-gray-50/30">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <div class="font-semibold text-gray-800">{{ $schedule->day }}</div>
                    <div class="text-xs text-gray-500">{{ $schedule->mass_type }} • {{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                </div>
                <div class="p-4">
                    <ul class="space-y-2">
                        @forelse($schedule->members as $member)
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-gray-700">{{ $member->last_name }}, {{ $member->first_name }}</span>
                            @if($isChecked)
                                @if($member->pivot->attendance_status === 'Present')
                                    <span class="text-xs text-green-600 font-medium">Present</span>
                                @elseif($member->pivot->attendance_status === 'Absent')
                                    <span class="text-xs text-red-500 font-medium">Absent</span>
                                @elseif($member->pivot->attendance_status === 'Excused')
                                    <span class="text-xs text-yellow-600 font-medium">Excused</span>
                                @else
                                    <span class="text-xs text-gray-400 font-medium">—</span>
                                @endif
                            @endif
                        </li>
                        @empty
                        <li class="text-sm text-gray-400 italic">No members assigned.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm py-4">No weekday schedule for this week.</p>
            @endforelse
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
