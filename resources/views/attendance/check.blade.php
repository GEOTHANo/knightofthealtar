@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center">
        <a href="{{ route('attendance.index') }}" class="text-gray-400 hover:text-gray-600 mr-3 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Check Attendance</h1>
            <p class="text-sm text-gray-500 mt-1">Record attendance for the selected week.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('attendance.save') }}">
        @csrf

        <!-- Week Period -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Week Period</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Week Start (Saturday)</label>
                    <input type="date" name="week_start" value="{{ $weekStart->format('Y-m-d') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Week End (Friday)</label>
                    <input type="date" name="week_end" value="{{ $weekEnd->format('Y-m-d') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 bg-gray-50/80 border-b border-gray-100">
                            <th class="px-6 py-3 font-medium">#</th>
                            <th class="px-6 py-3 font-medium">Member Name</th>
                            <th class="px-6 py-3 font-medium text-center">Present</th>
                            <th class="px-6 py-3 font-medium text-center">Absent</th>
                            <th class="px-6 py-3 font-medium text-center">Excused</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeMembers as $index => $member)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                            <td class="px-6 py-3 text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-3">
                                <input type="hidden" name="attendance[{{ $index }}][member_id]" value="{{ $member->id }}">
                                <div class="flex items-center">
                                    <div class="bg-[#246b9c] text-white font-bold rounded-full w-7 h-7 flex items-center justify-center mr-3 text-xs shrink-0">
                                        {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $member->last_name }}, {{ $member->first_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <input type="radio" name="attendance[{{ $index }}][status]" value="Present" checked class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                            </td>
                            <td class="px-6 py-3 text-center">
                                <input type="radio" name="attendance[{{ $index }}][status]" value="Absent" class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                            </td>
                            <td class="px-6 py-3 text-center">
                                <input type="radio" name="attendance[{{ $index }}][status]" value="Excused" class="w-4 h-4 text-yellow-600 border-gray-300 focus:ring-yellow-500">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end">
            <button type="submit" class="bg-[#246b9c] hover:bg-[#1a547b] text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors shadow-sm">
                <i data-lucide="save" class="w-4 h-4 mr-2 inline"></i> Save Attendance
            </button>
        </div>
    </form>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
