@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Welcome back! Here's today's overview.</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Members</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalMembers }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg"><i data-lucide="users" class="w-5 h-5 text-blue-600"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Present This Week</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $presentThisWeek }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg"><i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Absent This Week</p>
                    <p class="text-2xl font-bold text-red-500 mt-1">{{ $absentThisWeek }}</p>
                </div>
                <div class="bg-red-50 p-3 rounded-lg"><i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Inactive Members</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $inactiveMembers }}</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg"><i data-lucide="user-x" class="w-5 h-5 text-yellow-600"></i></div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Monthly Attendance Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Monthly Attendance (Last 12 Months)</h3>
            <canvas id="monthlyChart" height="120"></canvas>
        </div>

        <!-- Member Status Distribution -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Member Status Distribution</h3>
            <canvas id="statusChart" height="200"></canvas>
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center"><span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>Active</div>
                    <span class="font-semibold">{{ $activePercent }}%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center"><span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>Inactive</div>
                    <span class="font-semibold">{{ $inactivePercent }}%</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center"><span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>Alumni</div>
                    <span class="font-semibold">{{ $alumniPercent }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Leaders & Birthdays -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Leaders / Non-Member Positions -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Leadership & Officers</h3>
            </div>
            @if($leaders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-100">
                            <th class="pb-3 font-medium">Name</th>
                            <th class="pb-3 font-medium">Position</th>
                            <th class="pb-3 font-medium">Status</th>
                            <th class="pb-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaders as $leader)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                            <td class="py-3">
                                <div class="flex items-center">
                                    <div class="bg-[#246b9c] text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3 text-xs">
                                        {{ strtoupper(substr($leader->first_name, 0, 1) . substr($leader->last_name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $leader->last_name }}, {{ $leader->first_name }}</span>
                                </div>
                            </td>
                            <td class="py-3">
                                @foreach($leader->positions as $pos)
                                    <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-0.5 rounded-md mr-1">{{ $pos->position_name }}</span>
                                @endforeach
                            </td>
                            <td class="py-3">
                                <span class="inline-block bg-green-50 text-green-700 text-xs px-2 py-0.5 rounded-full font-medium">{{ $leader->status->value ?? $leader->status }}</span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('members.show', $leader) }}" class="text-gray-400 hover:text-blue-600 p-1 inline-block" title="View Details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('members.edit', $leader) }}" class="text-gray-400 hover:text-yellow-600 p-1 inline-block" title="Edit">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('members.archive', $leader) }}" method="POST" class="inline" onsubmit="return confirm('Archive this member?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 p-1" title="Archive">
                                        <i data-lucide="archive" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-400 text-sm text-center py-8">No leadership positions assigned yet.</p>
            @endif
        </div>

        <!-- Birthdays this Month -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Birthdays This Month ({{ now()->format('F') }})</h3>
                <div class="bg-rose-50 p-2 rounded-lg text-rose-600">
                    <i data-lucide="cake" class="w-4.5 h-4.5"></i>
                </div>
            </div>
            @if($birthdaysThisMonth->count() > 0)
            <div class="overflow-y-auto max-h-[300px] space-y-3 flex-1 pr-1">
                @foreach($birthdaysThisMonth as $birthdayMember)
                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <div class="bg-rose-50 text-rose-600 font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3 text-xs">
                            {{ strtoupper(substr($birthdayMember->first_name, 0, 1) . substr($birthdayMember->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-800">{{ $birthdayMember->first_name }} {{ $birthdayMember->last_name }}</p>
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
                @endforeach
            </div>
            @else
            <div class="flex-1 flex flex-col items-center justify-center py-8">
                <i data-lucide="calendar" class="w-8 h-8 text-gray-300 mb-2"></i>
                <p class="text-gray-400 text-sm text-center">No birthdays this month.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Attendance Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($monthlyData)->pluck('label')) !!},
            datasets: [{
                label: 'Present',
                data: {!! json_encode(collect($monthlyData)->pluck('count')) !!},
                backgroundColor: 'rgba(36, 107, 156, 0.8)',
                borderRadius: 6,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
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
                backgroundColor: ['#3b82f6', '#eab308', '#9ca3af'],
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
