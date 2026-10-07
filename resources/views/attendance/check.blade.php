@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center">
        <a href="{{ route('attendance.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 mr-3 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Check Attendance</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Record attendance for the selected week.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('attendance.save') }}">
        @csrf

        <!-- Week Period -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-5 mb-6">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Week Period</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Week Start (Saturday)</label>
                    <input type="date" name="week_start" value="{{ $weekStart->format('Y-m-d') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Week End (Friday)</label>
                    <input type="date" name="week_end" value="{{ $weekEnd->format('Y-m-d') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 dark:text-slate-400 bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800">
                            <th class="px-6 py-3.5 font-medium">#</th>
                            <th class="px-6 py-3.5 font-medium">Member Name</th>
                            <th class="px-6 py-3.5 font-medium text-center">Present</th>
                            <th class="px-6 py-3.5 font-medium text-center">Absent</th>
                            <th class="px-6 py-3.5 font-medium text-center">Excused</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @foreach($activeMembers as $index => $member)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-3.5 text-slate-400 dark:text-slate-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-3.5">
                                <input type="hidden" name="attendance[{{ $index }}][member_id]" value="{{ $member->id }}">
                                <div class="flex items-center">
                                    <div class="bg-blue-600 text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3 text-xs shrink-0 shadow-xs">
                                        {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $member->last_name }}, {{ $member->first_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-center">
                                <input type="radio" name="attendance[{{ $index }}][status]" value="Present" checked class="w-4 h-4 text-blue-600 border-slate-300 dark:border-slate-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-3.5 text-center">
                                <input type="radio" name="attendance[{{ $index }}][status]" value="Absent" class="w-4 h-4 text-rose-600 border-slate-300 dark:border-slate-600 focus:ring-rose-500">
                            </td>
                            <td class="px-6 py-3.5 text-center">
                                <input type="radio" name="attendance[{{ $index }}][status]" value="Excused" class="w-4 h-4 text-amber-600 border-slate-300 dark:border-slate-600 focus:ring-amber-500">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all shadow-xs flex items-center">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i> Save Attendance
            </button>
        </div>
    </form>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
