@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Account Settings</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your account credentials and view your profile information.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Security Settings (Editable) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Security & Login</h3>
                <form method="POST" action="{{ route('settings.update') }}" class="space-y-4">
                    @csrf @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" value="{{ old('username', $member->username) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                    </div>
                    
                    <hr class="border-gray-100 my-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                        <p class="text-xs text-gray-500 mt-1">Required only if changing password.</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none">
                    </div>

                    <button type="submit" class="w-full bg-[#246b9c] hover:bg-[#1a547b] text-white font-semibold py-2.5 rounded-lg text-sm transition-colors shadow-sm mt-4">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>

        <!-- Profile Information (Read-Only) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Profile Information</h3>
                    <span class="text-xs text-gray-400">Read-only view</span>
                </div>
                
                <div class="flex items-center mb-6">
                    <div class="bg-[#246b9c] text-white font-bold rounded-full w-16 h-16 flex items-center justify-center mr-4 text-xl shrink-0">
                        {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">{{ $member->full_name }}</h4>
                        <div class="mt-1 space-x-1">
                            @foreach($member->positions as $pos)
                                <span class="inline-block bg-blue-50 text-blue-700 text-xs px-2 py-0.5 rounded-md">{{ $pos->position_name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div><span class="text-gray-500">First Name</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->first_name }}</p></div>
                    <div><span class="text-gray-500">Middle Name</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->middle_name ?? '—' }}</p></div>
                    <div><span class="text-gray-500">Last Name</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->last_name }}</p></div>
                    <div><span class="text-gray-500">Birth Date</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->birth_date ? $member->birth_date->format('F d, Y') : '—' }}</p></div>
                    <div><span class="text-gray-500">Gender</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->gender->value ?? $member->gender }}</p></div>
                    <div><span class="text-gray-500">Contact Number</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->contact_number ?? '—' }}</p></div>
                    <div class="md:col-span-2"><span class="text-gray-500">Complete Address</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->complete_address ?? '—' }}</p></div>
                    <div><span class="text-gray-500">Email</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->email_address ?? '—' }}</p></div>
                    <div><span class="text-gray-500">School Attended</span><p class="font-medium text-gray-800 mt-0.5">{{ $member->school_attended ?? '—' }}</p></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
