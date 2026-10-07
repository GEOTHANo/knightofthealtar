@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Members</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage all registered members.</p>
        </div>
        <a href="{{ route('members.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center justify-center transition-all shadow-xs self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Member
        </a>
    </div>

    <!-- Search Form -->
    <div class="flex justify-start">
        <form action="{{ route('members.index') }}" method="GET" class="w-full sm:w-80 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." class="w-full pl-10 pr-9 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none shadow-xs">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 dark:text-slate-500"></i>
            </div>
            @if(request('search'))
                <a href="{{ route('members.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-xl text-sm shadow-xs">{{ session('success') }}</div>
    @endif

    <!-- Members Table -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 dark:text-slate-400 bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800">
                        <th class="px-6 py-3.5 font-medium">Name</th>
                        <th class="px-6 py-3.5 font-medium">Birthdate</th>
                        <th class="px-6 py-3.5 font-medium">Position / Role</th>
                        <th class="px-6 py-3.5 font-medium">Status</th>
                        <th class="px-6 py-3.5 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($members as $member)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-blue-600 text-white font-bold rounded-full w-8 h-8 flex items-center justify-center mr-3 text-xs shrink-0 shadow-xs">
                                    {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $member->last_name }}, {{ $member->first_name }} {{ $member->middle_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $member->birth_date ? $member->birth_date->format('M d, Y') : '—' }}</td>
                        <td class="px-6 py-4">
                            @foreach($member->positions as $pos)
                                <span class="inline-block bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-xs px-2 py-0.5 rounded-md mr-1 border border-blue-200/50 dark:border-blue-800/40">{{ $pos->position_name }}</span>
                            @endforeach
                            @if($member->positions->isEmpty())
                                <span class="text-slate-400 dark:text-slate-500 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php 
                                $st = $member->status->value ?? $member->status;
                            @endphp
                            @if($st === 'Active')
                                <span class="inline-block bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-xs px-2.5 py-0.5 rounded-full font-medium border border-blue-200/50 dark:border-blue-800/40">Active</span>
                            @elseif($st === 'Inactive')
                                <span class="inline-block bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-xs px-2.5 py-0.5 rounded-full font-medium border border-amber-200/50 dark:border-amber-800/40">Inactive</span>
                            @else
                                <span class="inline-block bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2.5 py-0.5 rounded-full font-medium border border-slate-200 dark:border-slate-700">Alumni</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('members.show', $member) }}" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 p-1 inline-block" title="View Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            @if(($member->status->value ?? $member->status) === 'Active')
                            <form action="{{ route('members.archive', $member) }}" method="POST" class="inline" onsubmit="return confirm('Set this member to Inactive?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 p-1" title="Archive (Set Inactive)">
                                    <i data-lucide="archive" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">No members found. Click "Add Member" to create one.</td>
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
