@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center">
        <a href="{{ route('members.show', $member) }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 mr-3 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Edit Member</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $member->full_name }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-4 py-3 rounded-xl text-sm shadow-xs">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('members.update', $member) }}">
        @csrf @method('PUT')

        <!-- Account Information -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 mb-6">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Account Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Username <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $member->username) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">New Password <span class="text-slate-400 dark:text-slate-500 text-xs">(leave blank to keep current)</span></label>
                    <input type="password" name="password" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 mb-6">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">First Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $member->middle_name) }}" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Last Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $member->contact_number) }}" placeholder="e.g. 09XX-XXX-XXXX" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Birthday</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $member->birth_date?->format('Y-m-d')) }}" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="Active" {{ old('status', $member->status->value ?? $member->status) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $member->status->value ?? $member->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Alumni" {{ old('status', $member->status->value ?? $member->status) == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Position(s) -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 mb-6">
            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Position(s)</h3>
            <div class="flex flex-wrap gap-2.5">
                @php $currentPositionIds = $member->positions->pluck('id')->toArray(); @endphp
                @foreach($positions as $position)
                <label class="inline-flex items-center bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 rounded-xl px-3.5 py-2 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-950/40 hover:border-blue-300 dark:hover:border-blue-800 transition-colors">
                    <input type="checkbox" name="position_ids[]" value="{{ $position->id }}" class="mr-2.5 rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500" {{ in_array($position->id, old('position_ids', $currentPositionIds)) ? 'checked' : '' }}>
                    <span class="text-sm text-slate-800 dark:text-slate-200">{{ $position->position_name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('members.show', $member) }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Cancel</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-xs">Update Member</button>
        </div>
    </form>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
