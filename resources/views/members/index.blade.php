@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Members</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all registered members.</p>
        </div>
        <a href="{{ route('members.create') }}" class="bg-[#246b9c] hover:bg-[#1a547b] text-white text-sm font-semibold px-4 py-2.5 rounded-lg flex items-center justify-center transition-colors shadow-sm self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Member
        </a>
    </div>

    <!-- Search Form -->
    <div class="flex justify-start">
        <form action="{{ route('members.index') }}" method="GET" class="w-full sm:w-1/3 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
            </div>
            @if(request('search'))
                <a href="{{ route('members.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <!-- Members Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 bg-gray-50/80 border-b border-gray-100">
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Birthdate</th>
                        <th class="px-6 py-3 font-medium">Position / Role</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-[#246b9c] text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3 text-xs shrink-0">
                                    {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $member->last_name }}, {{ $member->first_name }} {{ $member->middle_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $member->birth_date ? $member->birth_date->format('M d, Y') : '—' }}</td>
                        <td class="px-6 py-4">
                            @foreach($member->positions as $pos)
                                <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-0.5 rounded-md mr-1">{{ $pos->position_name }}</span>
                            @endforeach
                            @if($member->positions->isEmpty())
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php $statusColors = ['Active' => 'green', 'Inactive' => 'yellow', 'Alumni' => 'gray']; $c = $statusColors[$member->status->value ?? $member->status] ?? 'gray'; @endphp
                            <span class="inline-block bg-{{ $c }}-50 text-{{ $c }}-700 text-xs px-2 py-0.5 rounded-full font-medium">{{ $member->status->value ?? $member->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('members.show', $member) }}" class="text-gray-400 hover:text-blue-600 p-1 inline-block" title="View Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            @if(($member->status->value ?? $member->status) === 'Active')
                            <form action="{{ route('members.archive', $member) }}" method="POST" class="inline" onsubmit="return confirm('Set this member to Inactive?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-gray-400 hover:text-red-600 p-1" title="Archive (Set Inactive)">
                                    <i data-lucide="archive" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">No members found. Click "Add Member" to create one.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($members->hasPages())
    <div class="mt-4">
        {{ $members->links() }}
    </div>
    @endif
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
