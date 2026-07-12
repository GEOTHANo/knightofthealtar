@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center">
        <a href="{{ route('schedules.index') }}" class="text-gray-400 hover:text-gray-600 mr-3 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Check Pending Schedules</h1>
            <p class="text-sm text-gray-500 mt-1">Record attendance for members assigned to masses.</p>
        </div>
    </div>

    @if($weekdaySchedules->isEmpty() && $sundaySchedules->isEmpty())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
        <div class="bg-green-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="check-circle" class="w-8 h-8 text-green-500"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-1">All caught up!</h3>
        <p class="text-gray-500 text-sm">There are no pending schedules that need attendance checking.</p>
    </div>
    @else
    <form method="POST" action="{{ route('schedules.submitCheck') }}">
        @csrf

        @if($sundaySchedules->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Sunday Schedules (Pending)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sundaySchedules as $schedule)
                <div class="border border-yellow-100 rounded-lg overflow-hidden">
                    <div class="bg-yellow-50 px-4 py-3 border-b border-yellow-100">
                        <div class="font-semibold text-gray-800">{{ $schedule->mass_name }}</div>
                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($schedule->mass_date)->format('M d, Y') }} • {{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-sm">
                            <tbody>
                                @forelse($schedule->members as $member)
                                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50">
                                    <td class="px-4 py-2">{{ $member->last_name }}, {{ $member->first_name }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <select name="sunday_attendance[{{ $schedule->id }}][{{ $member->id }}]" required class="text-xs px-2 py-1 border border-gray-300 rounded focus:ring-[#246b9c] outline-none">
                                            <option value="Present">Present</option>
                                            <option value="Absent">Absent</option>
                                            <option value="Excused">Excused</option>
                                        </select>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="px-4 py-4 text-center text-gray-400 text-xs italic">No members assigned.</td></tr>
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
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Weekday Schedules (Pending)</h3>
            
            @foreach($weekdaySchedules as $weekStart => $schedules)
            <div class="mb-6 last:mb-0">
                <h4 class="text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wider">Week of {{ \Carbon\Carbon::parse($weekStart)->format('M d, Y') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($schedules as $schedule)
                    <div class="border border-gray-100 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                            <div class="font-semibold text-gray-800">{{ $schedule->day }}</div>
                            <div class="text-xs text-gray-500">{{ $schedule->mass_type }} • {{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                        </div>
                        <div class="p-0">
                            <table class="w-full text-sm">
                                <tbody>
                                    @forelse($schedule->members as $member)
                                    <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50">
                                        <td class="px-4 py-2">{{ $member->last_name }}, {{ $member->first_name }}</td>
                                        <td class="px-4 py-2 text-right">
                                            <select name="weekday_attendance[{{ $schedule->id }}][{{ $member->id }}]" required class="text-xs px-2 py-1 border border-gray-300 rounded focus:ring-[#246b9c] outline-none">
                                                <option value="Present">Present</option>
                                                <option value="Absent">Absent</option>
                                                <option value="Excused">Excused</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="2" class="px-4 py-4 text-center text-gray-400 text-xs italic">No members assigned.</td></tr>
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
            <a href="{{ route('schedules.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm">
                <i data-lucide="check" class="w-4 h-4 mr-2 inline"></i> Submit Attendance
            </button>
        </div>
    </form>
    @endif
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
