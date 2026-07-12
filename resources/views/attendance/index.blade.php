@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Attendance</h1>
            <p class="text-sm text-gray-500 mt-1">Track and manage member attendance weekly.</p>
        </div>
        <a href="{{ route('attendance.check') }}" class="bg-[#246b9c] hover:bg-[#1a547b] text-white text-sm font-semibold px-4 py-2.5 rounded-lg flex items-center justify-center transition-colors shadow-sm self-start sm:self-auto">
            <i data-lucide="clipboard-check" class="w-4 h-4 mr-2"></i> Check Attendance
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Present</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $totalPresent }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg"><i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Absent</p>
                    <p class="text-2xl font-bold text-red-500 mt-1">{{ $totalAbsent }}</p>
                </div>
                <div class="bg-red-50 p-3 rounded-lg"><i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Excused</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $totalExcused }}</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg"><i data-lucide="alert-circle" class="w-5 h-5 text-yellow-600"></i></div>
            </div>
        </div>
    </div>

    <!-- Active Members List -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Active Members ({{ $activeMembers->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 bg-gray-50/80 border-b border-gray-100">
                        <th class="px-6 py-3 font-medium">#</th>
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeMembers as $index => $member)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-3 text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center">
                                <div class="bg-[#246b9c] text-white font-bold rounded-full w-7 h-7 flex items-center justify-center mr-3 text-xs shrink-0">
                                    {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $member->last_name }}, {{ $member->first_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-block bg-green-50 text-green-700 text-xs px-2 py-0.5 rounded-full font-medium">Active</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-12 text-center text-gray-400">No active members found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
