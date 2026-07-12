@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center">
        <a href="{{ route('members.show', $member) }}" class="text-gray-400 hover:text-gray-600 mr-3 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Member</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $member->full_name }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('members.update', $member) }}">
        @csrf @method('PUT')

        <!-- Account Information -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Account Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $member->username) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password <span class="text-gray-400 text-xs">(leave blank to keep current)</span></label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $member->middle_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $member->contact_number) }}" placeholder="e.g. 09XX-XXX-XXXX" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Birthday</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $member->birth_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none bg-white">
                        <option value="Active" {{ old('status', $member->status->value ?? $member->status) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $member->status->value ?? $member->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Alumni" {{ old('status', $member->status->value ?? $member->status) == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Position(s) -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">Position(s)</h3>
            <div class="flex flex-wrap gap-2">
                @php $currentPositionIds = $member->positions->pluck('id')->toArray(); @endphp
                @foreach($positions as $position)
                <label class="inline-flex items-center bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors">
                    <input type="checkbox" name="position_ids[]" value="{{ $position->id }}" class="mr-2 rounded border-gray-300 text-[#246b9c] focus:ring-[#246b9c]" {{ in_array($position->id, old('position_ids', $currentPositionIds)) ? 'checked' : '' }}>
                    <span class="text-sm">{{ $position->position_name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('members.show', $member) }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-[#246b9c] hover:bg-[#1a547b] rounded-lg transition-colors shadow-sm">Update Member</button>
        </div>
    </form>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
