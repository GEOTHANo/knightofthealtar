@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center">
        <a href="{{ route('schedules.index') }}" class="text-gray-400 hover:text-gray-600 mr-3 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create Schedule</h1>
            <p class="text-sm text-gray-500 mt-1">Assign members to weekday and Sunday masses for the week.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('schedules.store') }}">
        @csrf

        <!-- Week Selection -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Select Week</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Week Start (Saturday) <span class="text-red-500">*</span></label>
                    <input type="date" name="week_start" value="{{ $weekStart->format('Y-m-d') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Week End (Friday) <span class="text-red-500">*</span></label>
                    <input type="date" name="week_end" value="{{ $weekEnd->format('Y-m-d') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                </div>
            </div>
        </div>

        <!-- Sunday Masses Assignment -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Sunday Mass Assignments</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @forelse($sundayMasses as $mass)
                <div class="border border-yellow-100 rounded-lg p-4 bg-yellow-50/10">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $mass->mass_name }}</h4>
                            <p class="text-xs text-gray-500"><i data-lucide="clock" class="w-3 h-3 inline mr-1"></i>{{ \Carbon\Carbon::parse($mass->mass_time)->format('g:i A') }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Assign Members:</label>
                        <select name="sunday_masses[{{ $mass->id }}][members][]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none min-h-[120px]">
                            @foreach($eligibleMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->last_name }}, {{ $member->first_name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm py-4">No Sunday masses configured.</p>
                @endforelse
            </div>
        </div>

        <!-- Weekday Masses Assignment -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Weekday Mass Assignments</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($weekdayMasses as $mass)
                <div class="border border-gray-100 rounded-lg p-4 bg-gray-50/50">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $mass->day }}</h4>
                            <p class="text-xs text-gray-500">{{ $mass->mass_type }} • <i data-lucide="clock" class="w-3 h-3 inline mx-1"></i>{{ \Carbon\Carbon::parse($mass->mass_time)->format('g:i A') }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Assign Members:</label>
                        <select name="weekday_masses[{{ $mass->id }}][members][]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none min-h-[100px]">
                            @foreach($eligibleMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->last_name }}, {{ $member->first_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm py-4">No weekday masses configured.</p>
                @endforelse
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('schedules.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-[#246b9c] hover:bg-[#1a547b] rounded-lg transition-colors shadow-sm">Save Schedule</button>
        </div>
    </form>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
