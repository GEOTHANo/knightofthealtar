@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route('members.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 mr-3 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $member->full_name }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Member Details</p>
            </div>
        </div>
        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <a href="{{ route('members.edit', $member) }}" class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl flex items-center transition-all shadow-xs">
                <i data-lucide="pencil" class="w-4 h-4 mr-2"></i> Edit
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-xl text-sm shadow-xs">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 text-center">
            <div class="bg-blue-600 text-white font-bold rounded-full w-20 h-20 flex items-center justify-center mx-auto text-2xl shadow-md">
                {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mt-4">{{ $member->full_name }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ '@' . $member->username }}</p>
            <div class="mt-3">
                @php $st = $member->status->value ?? $member->status; @endphp
                @if($st === 'Active')
                    <span class="inline-block bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-xs px-3 py-1 rounded-full font-medium border border-blue-200/50 dark:border-blue-800/40">Active</span>
                @elseif($st === 'Inactive')
                    <span class="inline-block bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-xs px-3 py-1 rounded-full font-medium border border-amber-200/50 dark:border-amber-800/40">Inactive</span>
                @else
                    <span class="inline-block bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-3 py-1 rounded-full font-medium border border-slate-200 dark:border-slate-700">Alumni</span>
                @endif
            </div>
            <div class="mt-4 space-y-1">
                @foreach($member->positions as $pos)
                    <span class="inline-block bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-xs px-2.5 py-1 rounded-lg border border-blue-200/50 dark:border-blue-800/40 mr-1">{{ $pos->position_name }}</span>
                @endforeach
            </div>
        </div>

        <!-- Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Info -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6">
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div><span class="text-slate-500 dark:text-slate-400">First Name</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->first_name }}</p></div>
                    <div><span class="text-slate-500 dark:text-slate-400">Middle Name</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->middle_name ?? '—' }}</p></div>
                    <div><span class="text-slate-500 dark:text-slate-400">Last Name</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->last_name }}</p></div>
                    <div><span class="text-slate-500 dark:text-slate-400">Contact Number</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->contact_number ?? '—' }}</p></div>
                    <div><span class="text-slate-500 dark:text-slate-400">Birthday</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->birth_date ? $member->birth_date->format('F d, Y') : '—' }}</p></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
