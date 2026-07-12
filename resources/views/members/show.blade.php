@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route('members.index') }}" class="text-gray-400 hover:text-gray-600 mr-3 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $member->full_name }}</h1>
                <p class="text-sm text-gray-500 mt-1">Member Details</p>
            </div>
        </div>
        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <a href="{{ route('members.edit', $member) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center transition-colors">
                <i data-lucide="pencil" class="w-4 h-4 mr-2"></i> Edit
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 text-center">
            <div class="bg-[#246b9c] text-white font-bold rounded-full w-20 h-20 flex items-center justify-center mx-auto text-2xl">
                {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
            </div>
            <h3 class="text-lg font-bold text-gray-800 mt-4">{{ $member->full_name }}</h3>
            <p class="text-sm text-gray-500">{{ '@' . $member->username }}</p>
            <div class="mt-3">
                @php $statusColors = ['Active' => 'green', 'Inactive' => 'yellow', 'Alumni' => 'gray']; $c = $statusColors[$member->status->value ?? $member->status] ?? 'gray'; @endphp
                <span class="inline-block bg-{{ $c }}-50 text-{{ $c }}-700 text-xs px-3 py-1 rounded-full font-medium">{{ $member->status->value ?? $member->status }}</span>
            </div>
            <div class="mt-4 space-y-1">
                @foreach($member->positions as $pos)
                    <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-0.5 rounded-md">{{ $pos->position_name }}</span>
                @endforeach
            </div>
        </div>

        <!-- Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Info -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div><span class="text-gray-500">First Name</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->first_name }}</p></div>
                    <div><span class="text-gray-500">Middle Name</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->middle_name ?? '—' }}</p></div>
                    <div><span class="text-gray-500">Last Name</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->last_name }}</p></div>
                    <div><span class="text-gray-500">Contact Number</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->contact_number ?? '—' }}</p></div>
                    <div><span class="text-gray-500">Birthday</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->birth_date ? $member->birth_date->format('F d, Y') : '—' }}</p></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
