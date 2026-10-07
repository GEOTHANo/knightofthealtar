@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center">
        <a href="{{ route('schedules.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 mr-3 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Create Schedule</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Assign members to weekday and Sunday masses for the week.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-4 py-3 rounded-xl text-sm shadow-xs">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('schedules.store') }}">
        @csrf

        <!-- Week Selection -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 mb-6">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Select Week</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Week Start (Saturday) <span class="text-rose-500">*</span></label>
                    <input type="date" name="week_start" value="{{ $weekStart->format('Y-m-d') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Week End (Friday) <span class="text-rose-500">*</span></label>
                    <input type="date" name="week_end" value="{{ $weekEnd->format('Y-m-d') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Sunday Masses Assignment -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 mb-6">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Sunday Mass Assignments</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @forelse($sundayMasses as $mass)
                <div class="border border-amber-200/60 dark:border-amber-900/40 rounded-xl p-4 bg-amber-50/20 dark:bg-amber-950/20">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="font-semibold text-slate-800 dark:text-slate-100">{{ $mass->mass_name }}</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5"><i data-lucide="clock" class="w-3 h-3 inline mr-1"></i>{{ \Carbon\Carbon::parse($mass->mass_time)->format('g:i A') }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Assign Members:</label>
                        <select name="sunday_masses[{{ $mass->id }}][members][]" multiple class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none min-h-[120px]">
                            @foreach($eligibleMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->last_name }}, {{ $member->first_name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</p>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 dark:text-slate-500 text-sm py-4">No Sunday masses configured.</p>
                @endforelse
            </div>
        </div>

        <!-- Weekday Masses Assignment -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 mb-6">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Weekday Mass Assignments</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($weekdayMasses as $mass)
                <div class="border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 bg-slate-50/50 dark:bg-slate-800/40">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="font-semibold text-slate-800 dark:text-slate-100">{{ $mass->day }}</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $mass->mass_type }} • <i data-lucide="clock" class="w-3 h-3 inline mx-1"></i>{{ \Carbon\Carbon::parse($mass->mass_time)->format('g:i A') }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Assign Members:</label>
                        <select name="weekday_masses[{{ $mass->id }}][members][]" multiple class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none min-h-[100px]">
                            @foreach($eligibleMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->last_name }}, {{ $member->first_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 dark:text-slate-500 text-sm py-4">No weekday masses configured.</p>
                @endforelse
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('schedules.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Cancel</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-xs">Save Schedule</button>
        </div>
    </form>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
