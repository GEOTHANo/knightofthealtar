@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Dashboard</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Welcome back! Here's today's overview.</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Members</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $totalMembers }}</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-950/60 p-3 rounded-xl border border-blue-100/50 dark:border-blue-900/40">
                    <i data-lucide="users" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Present This Week</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $presentThisWeek }}</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-950/60 p-3 rounded-xl border border-blue-100/50 dark:border-blue-900/40">
                    <i data-lucide="check-circle" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Absent This Week</p>
                    <p class="text-2xl font-bold text-rose-500 dark:text-rose-400 mt-1">{{ $absentThisWeek }}</p>
                </div>
                <div class="bg-rose-50 dark:bg-rose-950/60 p-3 rounded-xl border border-rose-100/50 dark:border-rose-900/40">
                    <i data-lucide="x-circle" class="w-5 h-5 text-rose-500 dark:text-rose-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Inactive Members</p>
                    <p class="text-2xl font-bold text-amber-500 dark:text-amber-400 mt-1">{{ $inactiveMembers }}</p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-950/60 p-3 rounded-xl border border-amber-100/50 dark:border-amber-900/40">
                    <i data-lucide="user-x" class="w-5 h-5 text-amber-500 dark:text-amber-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Monthly Attendance Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4">Monthly Attendance (Last 12 Months)</h3>
            <canvas id="monthlyChart" height="120"></canvas>
        </div>

        <!-- Member Status Distribution -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4">Member Status Distribution</h3>
            <canvas id="statusChart" height="200"></canvas>
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center text-slate-700 dark:text-slate-300">
                        <span class="w-3 h-3 bg-blue-600 rounded-full mr-2"></span>Active
                    </div>
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $activePercent }}%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center text-slate-700 dark:text-slate-300">
                        <span class="w-3 h-3 bg-amber-500 rounded-full mr-2"></span>Inactive
                    </div>
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $inactivePercent }}%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center text-slate-700 dark:text-slate-300">
                        <span class="w-3 h-3 bg-slate-400 rounded-full mr-2"></span>Alumni
                    </div>
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $alumniPercent }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Leadership & Birthdays -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Leaders / Officers -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Leadership & Officers</h3>
            </div>
            @if($leaders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                            <th class="pb-3 font-medium">Name</th>
                            <th class="pb-3 font-medium">Position</th>
                            <th class="pb-3 font-medium">Status</th>
                            <th class="pb-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @foreach($leaders as $leader)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3">
                                <div class="flex items-center">
                                    <div class="bg-blue-600 text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3 text-xs shadow-xs">
                                        {{ strtoupper(substr($leader->first_name, 0, 1) . substr($leader->last_name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $leader->last_name }}, {{ $leader->first_name }}</span>
                                </div>
                            </td>
                            <td class="py-3">
                                @foreach($leader->positions as $pos)
                                    <span class="inline-block bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-xs px-2 py-0.5 rounded-md mr-1 border border-blue-200/50 dark:border-blue-800/40">{{ $pos->position_name }}</span>
                                @endforeach
                            </td>
                            <td class="py-3">
                                <span class="inline-block bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 text-xs px-2.5 py-0.5 rounded-full font-medium border border-blue-200/50 dark:border-blue-800/40">
                                    {{ $leader->status->value ?? $leader->status }}
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('members.show', $leader) }}" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 p-1 inline-block" title="View Details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('members.edit', $leader) }}" class="text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 p-1 inline-block" title="Edit">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('members.archive', $leader) }}" method="POST" class="inline" onsubmit="return confirm('Archive this member?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 p-1" title="Archive">
                                        <i data-lucide="archive" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($leaders->hasPages())
            <div class="mt-4">
                {{ $leaders->links() }}
            </div>
            @endif
            @else
            <p class="text-slate-400 dark:text-slate-500 text-sm text-center py-8">No leadership positions assigned yet.</p>
            @endif
        </div>

        <!-- Birthdays this Month -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Birthdays This Month ({{ now()->format('F') }})</h3>
                <div class="bg-rose-50 dark:bg-rose-950/60 p-2 rounded-xl text-rose-600 dark:text-rose-400 border border-rose-100/50 dark:border-rose-900/40">
                    <i data-lucide="cake" class="w-4.5 h-4.5"></i>
                </div>
            </div>
            @if($birthdaysThisMonth->count() > 0)
            <div class="overflow-y-auto max-h-[300px] space-y-3 flex-1 pr-1">
                @foreach($birthdaysThisMonth as $birthdayMember)
                <div class="flex items-center justify-between p-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-xl transition-colors">
                    <div class="flex items-center">
                        <div class="bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3 text-xs border border-rose-200/50 dark:border-rose-800/40">
                            {{ strtoupper(substr($birthdayMember->first_name, 0, 1) . substr($birthdayMember->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-slate-800 dark:text-slate-200">{{ $birthdayMember->first_name }} {{ $birthdayMember->last_name }}</p>
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
                @endforeach
            </div>
            @else
            <div class="flex-1 flex flex-col items-center justify-center py-8">
                <i data-lucide="calendar" class="w-8 h-8 text-slate-300 dark:text-slate-600 mb-2"></i>
                <p class="text-slate-400 dark:text-slate-500 text-sm text-center">No birthdays this month.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? '#334155' : '#f1f5f9';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    // Monthly Attendance Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($monthlyData)->pluck('label')) !!},
            datasets: [{
                label: 'Present',
                data: {!! json_encode(collect($monthlyData)->pluck('count')) !!},
                backgroundColor: 'rgba(37, 99, 235, 0.85)',
                borderRadius: 6,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } },
                x: { grid: { display: false }, ticks: { color: textColor } }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive', 'Alumni'],
            datasets: [{
                data: [{{ $activePercent }}, {{ $inactivePercent }}, {{ $alumniPercent }}],
                backgroundColor: ['#2563eb', '#f59e0b', '#64748b'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });

    lucide.createIcons();
});
</script>
@endsection
