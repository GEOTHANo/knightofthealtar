@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Mass Configuration</h1>
            <p class="text-sm text-gray-500 mt-1">Manage weekday and Sunday mass schedules.</p>
        </div>
        <button onclick="document.getElementById('addMassModal').classList.remove('hidden')" class="bg-[#246b9c] hover:bg-[#1a547b] text-white text-sm font-semibold px-4 py-2.5 rounded-lg flex items-center justify-center transition-colors shadow-sm self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Mass
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <!-- Sunday Masses -->
    <div>
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Sunday Masses</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($sundayMasses as $mass)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 relative group hover:shadow-md transition-shadow">
                <div class="absolute top-3 right-3 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="openEditSunday({{ $mass->id }}, '{{ addslashes($mass->mass_name) }}', '{{ $mass->mass_time }}', '{{ addslashes($mass->description) }}')" class="text-gray-400 hover:text-yellow-600 p-1" title="Edit">
                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                    </button>
                    <form action="{{ route('mass.sunday.archive', $mass) }}" method="POST" class="inline" onsubmit="return confirm('Archive this Sunday mass?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-gray-400 hover:text-red-600 p-1" title="Archive"><i data-lucide="archive" class="w-3.5 h-3.5"></i></button>
                    </form>
                </div>
                <div class="flex items-center mb-3">
                    <div class="bg-yellow-50 p-2 rounded-lg mr-3"><i data-lucide="sun" class="w-5 h-5 text-yellow-600"></i></div>
                    <div>
                        <h4 class="font-semibold text-gray-800 text-sm">{{ $mass->mass_name }}</h4>
                        <p class="text-xs text-gray-500">Sunday</p>
                    </div>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i data-lucide="clock" class="w-4 h-4 mr-1.5 text-gray-400"></i>
                    {{ \Carbon\Carbon::parse($mass->mass_time)->format('g:i A') }}
                </div>
                @if($mass->description)
                <p class="text-xs text-gray-400 mt-2">{{ $mass->description }}</p>
                @endif
            </div>
            @empty
            <p class="text-gray-400 text-sm col-span-3 text-center py-8">No Sunday masses configured yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Weekday Masses -->
    <div>
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Weekday Masses</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($weekdayMasses as $mass)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 relative group hover:shadow-md transition-shadow">
                <div class="absolute top-3 right-3 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="openEditWeekday({{ $mass->id }}, '{{ $mass->day }}', '{{ $mass->mass_type }}', '{{ $mass->mass_time }}', '{{ addslashes($mass->description) }}')" class="text-gray-400 hover:text-yellow-600 p-1" title="Edit">
                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                    </button>
                    <form action="{{ route('mass.weekday.archive', $mass) }}" method="POST" class="inline" onsubmit="return confirm('Archive this weekday mass?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-gray-400 hover:text-red-600 p-1" title="Archive"><i data-lucide="archive" class="w-3.5 h-3.5"></i></button>
                    </form>
                </div>
                <div class="flex items-center mb-3">
                    <div class="bg-blue-50 p-2 rounded-lg mr-3"><i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i></div>
                    <div>
                        <h4 class="font-semibold text-gray-800 text-sm">{{ $mass->day }} Mass</h4>
                        <p class="text-xs text-gray-500">{{ $mass->mass_type }}</p>
                    </div>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i data-lucide="clock" class="w-4 h-4 mr-1.5 text-gray-400"></i>
                    {{ \Carbon\Carbon::parse($mass->mass_time)->format('g:i A') }}
                </div>
                @if($mass->description)
                <p class="text-xs text-gray-400 mt-2">{{ $mass->description }}</p>
                @endif
            </div>
            @empty
            <p class="text-gray-400 text-sm col-span-3 text-center py-8">No weekday masses configured yet.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Add Mass Modal -->
<div id="addMassModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Add Mass</h2>
            <button onclick="document.getElementById('addMassModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-6 space-y-6">
            <!-- Sunday Mass Form -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Add Sunday Mass</h3>
                <form method="POST" action="{{ route('mass.sunday.store') }}" class="space-y-3">
                    @csrf
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Mass Name</label><input type="text" name="mass_name" value="Sunday Mass" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Time</label><input type="time" name="mass_time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Description</label><input type="text" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none"></div>
                    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 rounded-lg text-sm transition-colors">Add Sunday Mass</button>
                </form>
            </div>
            <hr class="border-gray-100">
            <!-- Weekday Mass Form -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Add Weekday Mass</h3>
                <form method="POST" action="{{ route('mass.weekday.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Day</label>
                        <select name="day" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none bg-white">
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mass Type</label>
                        <select name="mass_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none bg-white">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Time</label><input type="time" name="mass_time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Description</label><input type="text" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none"></div>
                    <button type="submit" class="w-full bg-[#246b9c] hover:bg-[#1a547b] text-white font-semibold py-2 rounded-lg text-sm transition-colors">Add Weekday Mass</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Weekday Modal -->
<div id="editWeekdayModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Edit Weekday Mass</h2>
            <button onclick="document.getElementById('editWeekdayModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="editWeekdayForm" method="POST" class="p-6 space-y-3">
            @csrf @method('PUT')
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Day</label><select name="day" id="editWDay" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none bg-white">@foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $d)<option value="{{ $d }}">{{ $d }}</option>@endforeach</select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Mass Type</label><select name="mass_type" id="editWType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none bg-white"><option value="AM">AM</option><option value="PM">PM</option></select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Time</label><input type="time" name="mass_time" id="editWTime" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Description</label><input type="text" name="description" id="editWDesc" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none"></div>
            <button type="submit" class="w-full bg-[#246b9c] hover:bg-[#1a547b] text-white font-semibold py-2 rounded-lg text-sm transition-colors">Update</button>
        </form>
    </div>
</div>

<!-- Edit Sunday Modal -->
<div id="editSundayModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Edit Sunday Mass</h2>
            <button onclick="document.getElementById('editSundayModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="editSundayForm" method="POST" class="p-6 space-y-3">
            @csrf @method('PUT')
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Mass Name</label><input type="text" name="mass_name" id="editSName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Time</label><input type="time" name="mass_time" id="editSTime" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Description</label><input type="text" name="description" id="editSDesc" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none"></div>
            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 rounded-lg text-sm transition-colors">Update</button>
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
