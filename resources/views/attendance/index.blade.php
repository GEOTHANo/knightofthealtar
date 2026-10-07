@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Attendance</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Track and manage member attendance weekly.</p>
        </div>
        <a href="{{ route('attendance.check') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center justify-center transition-all shadow-xs self-start sm:self-auto">
            <i data-lucide="clipboard-check" class="w-4 h-4 mr-2"></i> Check Attendance
        </a>
    </div>

    @if(session('success'))
        <div class="bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-xl text-sm shadow-xs">{{ session('success') }}</div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Present</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $totalPresent }}</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-950/60 p-3 rounded-xl border border-blue-100/50 dark:border-blue-900/40"><i data-lucide="check-circle" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i></div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Absent</p>
                    <p class="text-2xl font-bold text-rose-500 dark:text-rose-400 mt-1">{{ $totalAbsent }}</p>
                </div>
                <div class="bg-rose-50 dark:bg-rose-950/60 p-3 rounded-xl border border-rose-100/50 dark:border-rose-900/40"><i data-lucide="x-circle" class="w-5 h-5 text-rose-500 dark:text-rose-400"></i></div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Excused</p>
                    <p class="text-2xl font-bold text-amber-500 dark:text-amber-400 mt-1">{{ $totalExcused }}</p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-950/60 p-3 rounded-xl border border-amber-100/50 dark:border-amber-900/40"><i data-lucide="alert-circle" class="w-5 h-5 text-amber-500 dark:text-amber-400"></i></div>
            </div>
        </div>
    </div>

    <!-- Active Members List -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Active Members ({{ $activeMembers->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 dark:text-slate-400 bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800">
                        <th class="px-6 py-3.5 font-medium">#</th>
                        <th class="px-6 py-3.5 font-medium">Name</th>
                        <th class="px-6 py-3.5 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($activeMembers as $index => $member)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-3.5 text-slate-400 dark:text-slate-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center">
                                <div class="bg-blue-600 text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3 text-xs shrink-0 shadow-xs">
                                    {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $member->last_name }}, {{ $member->first_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-block bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-xs px-2.5 py-0.5 rounded-full font-medium border border-blue-200/50 dark:border-blue-800/40">Active</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">No active members found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
