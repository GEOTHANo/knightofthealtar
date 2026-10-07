@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Schedules</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage weekly mass schedules and assigned members.</p>
        </div>
        @if(auth()->user()->canAccess('schedules.create'))
        <div class="flex space-x-3 self-start sm:self-auto">
            <a href="{{ route('schedules.check') }}" class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center transition-all shadow-xs">
                <i data-lucide="check-square" class="w-4 h-4 mr-2"></i> Check Schedule
            </a>
            <a href="{{ route('schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center transition-all shadow-xs">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Create Schedule
            </a>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-xl text-sm shadow-xs">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        @forelse($allWeeks as $weekStart => $data)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-slate-100">Week of {{ \Carbon\Carbon::parse($weekStart)->format('M d, Y') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ $data['weekday']->count() }} Weekday Masses, {{ $data['sunday']->count() }} Sunday Masses
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($data['status'] === 'checked')
                            <span class="inline-flex items-center bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-xs px-3 py-1 rounded-full font-medium border border-blue-200/50 dark:border-blue-800/40">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5 mr-1 text-blue-600 dark:text-blue-400"></i> Checked
                            </span>
                        @else
                            <span class="inline-flex items-center bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-xs px-3 py-1 rounded-full font-medium border border-amber-200/50 dark:border-amber-800/40">
                                <i data-lucide="clock" class="w-3.5 h-3.5 mr-1 text-amber-600 dark:text-amber-400"></i> Not Checked
                            </span>
                        @endif
                        <a href="{{ route('schedules.show', $weekStart) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold">View Details</a>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Weekday Preview -->
                    @if($data['weekday']->isNotEmpty())
                        <h4 class="text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider mb-3">Weekday Schedule</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
                            @foreach($data['weekday']->take(3) as $schedule)
                                <div class="border border-slate-200/60 dark:border-slate-800 rounded-xl p-3 bg-slate-50/50 dark:bg-slate-800/40">
                                    <div class="font-medium text-sm text-slate-800 dark:text-slate-200">{{ $schedule->day }} ({{ $schedule->mass_type }})</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                                    <div class="text-xs text-blue-600 dark:text-blue-400 mt-2 font-medium">{{ $schedule->members->count() }} members assigned</div>
                                </div>
                            @endforeach
                            @if($data['weekday']->count() > 3)
                                <div class="border border-dashed border-slate-300 dark:border-slate-700 rounded-xl p-3 flex items-center justify-center text-slate-400 dark:text-slate-500 text-sm">
                                    + {{ $data['weekday']->count() - 3 }} more
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Sunday Preview -->
                    @if($data['sunday']->isNotEmpty())
                        <h4 class="text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider mb-3">Sunday Schedule</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($data['sunday']->take(3) as $schedule)
                                <div class="border border-amber-200/60 dark:border-amber-900/40 rounded-xl p-3 bg-amber-50/30 dark:bg-amber-950/20">
                                    <div class="font-medium text-sm text-slate-800 dark:text-slate-200">{{ $schedule->mass_name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ \Carbon\Carbon::parse($schedule->mass_date)->format('M d') }} • {{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                                    <div class="text-xs text-amber-600 dark:text-amber-400 mt-2 font-medium">{{ $schedule->members->count() }} members assigned</div>
                                </div>
                            @endforeach
                            @if($data['sunday']->count() > 3)
                                <div class="border border-dashed border-amber-300 dark:border-amber-800 rounded-xl p-3 flex items-center justify-center text-amber-600 dark:text-amber-400 text-sm bg-amber-50/10">
                                    + {{ $data['sunday']->count() - 3 }} more
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-12 text-center">
                <div class="bg-slate-50 dark:bg-slate-800 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-200/60 dark:border-slate-700">
                    <i data-lucide="calendar" class="w-8 h-8 text-slate-400 dark:text-slate-500"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-1">No schedules found</h3>
                @if(auth()->user()->canAccess('schedules.create'))
                <p class="text-slate-500 dark:text-slate-400 text-sm mb-4">Get started by creating a new schedule for the upcoming week.</p>
                <a href="{{ route('schedules.create') }}" class="inline-flex items-center text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700">
                    Create Schedule <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                </a>
                @else
                <p class="text-slate-500 dark:text-slate-400 text-sm">Please wait for the coordinator or secretary to create a schedule.</p>
                @endif
            </div>
        @endforelse
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
