@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Mass Configuration</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage weekday and Sunday mass schedules.</p>
        </div>
        <button onclick="document.getElementById('addMassModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center justify-center transition-all shadow-xs self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Mass
        </button>
    </div>

    @if(session('success'))
        <div class="bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-xl text-sm shadow-xs">{{ session('success') }}</div>
    @endif

    <!-- Sunday Masses -->
    <div>
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-3">Sunday Masses</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($sundayMasses as $mass)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-5 relative group hover:shadow-md transition-all">
                <div class="absolute top-3 right-3 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="openEditSunday({{ $mass->id }}, '{{ addslashes($mass->mass_name) }}', '{{ $mass->mass_time }}', '{{ addslashes($mass->description) }}')" class="text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 p-1" title="Edit">
                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                    </button>
                    <form action="{{ route('mass.sunday.archive', $mass) }}" method="POST" class="inline" onsubmit="return confirm('Archive this Sunday mass?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 p-1" title="Archive"><i data-lucide="archive" class="w-3.5 h-3.5"></i></button>
                    </form>
                </div>
                <div class="flex items-center mb-3">
                    <div class="bg-amber-50 dark:bg-amber-950/60 p-2.5 rounded-xl mr-3 border border-amber-100/50 dark:border-amber-900/40"><i data-lucide="sun" class="w-5 h-5 text-amber-500 dark:text-amber-400"></i></div>
                    <div>
                        <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $mass->mass_name }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Sunday</p>
                    </div>
                </div>
                <div class="flex items-center text-sm text-slate-600 dark:text-slate-400">
                    <i data-lucide="clock" class="w-4 h-4 mr-1.5 text-slate-400 dark:text-slate-500"></i>
                    {{ \Carbon\Carbon::parse($mass->mass_time)->format('g:i A') }}
                </div>
                @if($mass->description)
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">{{ $mass->description }}</p>
                @endif
            </div>
            @empty
            <p class="text-slate-400 dark:text-slate-500 text-sm col-span-3 text-center py-8">No Sunday masses configured yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Weekday Masses -->
    <div>
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-3">Weekday Masses</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($weekdayMasses as $mass)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-5 relative group hover:shadow-md transition-all">
                <div class="absolute top-3 right-3 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="openEditWeekday({{ $mass->id }}, '{{ $mass->day }}', '{{ $mass->mass_type }}', '{{ $mass->mass_time }}', '{{ addslashes($mass->description) }}')" class="text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 p-1" title="Edit">
                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                    </button>
                    <form action="{{ route('mass.weekday.archive', $mass) }}" method="POST" class="inline" onsubmit="return confirm('Archive this weekday mass?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 p-1" title="Archive"><i data-lucide="archive" class="w-3.5 h-3.5"></i></button>
                    </form>
                </div>
                <div class="flex items-center mb-3">
                    <div class="bg-blue-50 dark:bg-blue-950/60 p-2.5 rounded-xl mr-3 border border-blue-100/50 dark:border-blue-900/40"><i data-lucide="calendar" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i></div>
                    <div>
                        <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $mass->day }} Mass</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $mass->mass_type }}</p>
                    </div>
                </div>
                <div class="flex items-center text-sm text-slate-600 dark:text-slate-400">
                    <i data-lucide="clock" class="w-4 h-4 mr-1.5 text-slate-400 dark:text-slate-500"></i>
                    {{ \Carbon\Carbon::parse($mass->mass_time)->format('g:i A') }}
                </div>
                @if($mass->description)
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">{{ $mass->description }}</p>
                @endif
            </div>
            @empty
            <p class="text-slate-400 dark:text-slate-500 text-sm col-span-3 text-center py-8">No weekday masses configured yet.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Add Mass Modal -->
<div id="addMassModal" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Add Mass</h2>
            <button onclick="document.getElementById('addMassModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-6 space-y-6">
            <!-- Sunday Mass Form -->
            <div>
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Add Sunday Mass</h3>
                <form method="POST" action="{{ route('mass.sunday.store') }}" class="space-y-3">
                    @csrf
                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mass Name</label><input type="text" name="mass_name" value="Sunday Mass" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none"></div>
                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Time</label><input type="time" name="mass_time" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none"></div>
                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description</label><input type="text" name="description" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none"></div>
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2.5 rounded-xl text-sm transition-all shadow-xs">Add Sunday Mass</button>
                </form>
            </div>
            <hr class="border-slate-100 dark:border-slate-800">
            <!-- Weekday Mass Form -->
            <div>
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Add Weekday Mass</h3>
                <form method="POST" action="{{ route('mass.weekday.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Day</label>
                        <select name="day" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mass Type</label>
                        <select name="mass_type" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Time</label><input type="time" name="mass_time" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none"></div>
                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description</label><input type="text" name="description" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none"></div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-all shadow-xs">Add Weekday Mass</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Weekday Modal -->
<div id="editWeekdayModal" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-lg">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Edit Weekday Mass</h2>
            <button onclick="document.getElementById('editWeekdayModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="editWeekdayForm" method="POST" class="p-6 space-y-3">
            @csrf @method('PUT')
            <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Day</label><select name="day" id="editWDay" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">@foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $d)<option value="{{ $d }}">{{ $d }}</option>@endforeach</select></div>
            <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mass Type</label><select name="mass_type" id="editWType" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none"><option value="AM">AM</option><option value="PM">PM</option></select></div>
            <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Time</label><input type="time" name="mass_time" id="editWTime" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description</label><input type="text" name="description" id="editWDesc" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-all shadow-xs">Update</button>
        </form>
    </div>
</div>

<!-- Edit Sunday Modal -->
<div id="editSundayModal" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-lg">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Edit Sunday Mass</h2>
            <button onclick="document.getElementById('editSundayModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="editSundayForm" method="POST" class="p-6 space-y-3">
            @csrf @method('PUT')
            <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mass Name</label><input type="text" name="mass_name" id="editSName" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Time</label><input type="time" name="mass_time" id="editSTime" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description</label><input type="text" name="description" id="editSDesc" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2.5 rounded-xl text-sm transition-all shadow-xs">Update</button>
        </form>
    </div>
</div>

<script>
function openEditWeekday(id, day, type, time, desc) {
    document.getElementById('editWeekdayForm').action = '/mass/weekday/' + id;
    document.getElementById('editWDay').value = day;
    document.getElementById('editWType').value = type;
    document.getElementById('editWTime').value = time;
    document.getElementById('editWDesc').value = desc;
    document.getElementById('editWeekdayModal').classList.remove('hidden');
    lucide.createIcons();
}
function openEditSunday(id, name, time, desc) {
    document.getElementById('editSundayForm').action = '/mass/sunday/' + id;
    document.getElementById('editSName').value = name;
    document.getElementById('editSTime').value = time;
    document.getElementById('editSDesc').value = desc;
    document.getElementById('editSundayModal').classList.remove('hidden');
    lucide.createIcons();
}
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endsection
