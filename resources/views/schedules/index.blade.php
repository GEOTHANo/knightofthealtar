@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Schedules</h1>
            <p class="text-sm text-gray-500 mt-1">Manage weekly mass schedules and assigned members.</p>
        </div>
        @if(auth()->user()->canAccess('schedules.create'))
        <div class="flex space-x-3 self-start sm:self-auto">
            <a href="{{ route('schedules.check') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg flex items-center transition-colors shadow-sm">
                <i data-lucide="check-square" class="w-4 h-4 mr-2"></i> Check Schedule
            </a>
            <a href="{{ route('schedules.create') }}" class="bg-[#246b9c] hover:bg-[#1a547b] text-white text-sm font-semibold px-4 py-2.5 rounded-lg flex items-center transition-colors shadow-sm">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Create Schedule
            </a>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="space-y-6">
        @forelse($allWeeks as $weekStart => $data)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800">Week of {{ \Carbon\Carbon::parse($weekStart)->format('M d, Y') }}</h3>
                        <p class="text-xs text-gray-500">
                            {{ $data['weekday']->count() }} Weekday Masses, {{ $data['sunday']->count() }} Sunday Masses
                        </p>
                    </div>
                    <div>
                        @if($data['status'] === 'checked')
                            <span class="inline-flex items-center bg-green-50 text-green-700 text-xs px-2.5 py-1 rounded-full font-medium">
                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> Checked
                            </span>
                        @else
                            <span class="inline-flex items-center bg-yellow-50 text-yellow-700 text-xs px-2.5 py-1 rounded-full font-medium">
                                <i data-lucide="clock" class="w-3 h-3 mr-1"></i> Not Checked
                            </span>
                        @endif
                        <a href="{{ route('schedules.show', $weekStart) }}" class="ml-3 text-sm text-blue-600 hover:text-blue-800 font-medium">View Details</a>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Weekday Preview -->
                    @if($data['weekday']->isNotEmpty())
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Weekday Schedule</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
                            @foreach($data['weekday']->take(3) as $schedule)
                                <div class="border border-gray-100 rounded-lg p-3 bg-gray-50">
                                    <div class="font-medium text-sm text-gray-800">{{ $schedule->day }} ({{ $schedule->mass_type }})</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                                    <div class="text-xs text-blue-600 mt-2 font-medium">{{ $schedule->members->count() }} members assigned</div>
                                </div>
                            @endforeach
                            @if($data['weekday']->count() > 3)
                                <div class="border border-dashed border-gray-200 rounded-lg p-3 flex items-center justify-center text-gray-400 text-sm">
                                    + {{ $data['weekday']->count() - 3 }} more
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Sunday Preview -->
                    @if($data['sunday']->isNotEmpty())
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Sunday Schedule</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($data['sunday']->take(3) as $schedule)
                                <div class="border border-yellow-100 rounded-lg p-3 bg-yellow-50/30">
                                    <div class="font-medium text-sm text-gray-800">{{ $schedule->mass_name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($schedule->mass_date)->format('M d') }} • {{ \Carbon\Carbon::parse($schedule->mass_time)->format('g:i A') }}</div>
                                    <div class="text-xs text-yellow-600 mt-2 font-medium">{{ $schedule->members->count() }} members assigned</div>
                                </div>
                            @endforeach
                            @if($data['sunday']->count() > 3)
                                <div class="border border-dashed border-yellow-200 rounded-lg p-3 flex items-center justify-center text-yellow-600/50 text-sm bg-yellow-50/10">
                                    + {{ $data['sunday']->count() - 3 }} more
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="calendar" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No schedules found</h3>
                @if(auth()->user()->canAccess('schedules.create'))
                <p class="text-gray-500 text-sm mb-4">Get started by creating a new schedule for the upcoming week.</p>
                <a href="{{ route('schedules.create') }}" class="inline-flex items-center text-sm font-medium text-[#246b9c] hover:text-[#1a547b]">
                    Create Schedule <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                </a>
                @else
                <p class="text-gray-500 text-sm">Please wait for the coordinator or secretary to create a schedule.</p>
                @endif
            </div>
        @endforelse
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
