@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Account Settings</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage your account credentials and view your profile information.</p>
    </div>

    @if(session('success'))
        <div class="bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-xl text-sm shadow-xs">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-4 py-3 rounded-xl text-sm shadow-xs">
            <ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Security Settings (Editable) & Theme Toggle -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Security & Login</h3>
                <form method="POST" action="{{ route('settings.update') }}" class="space-y-4">
                    @csrf @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Username</label>
                        <input type="text" name="username" value="{{ old('username', $member->username) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    
                    <hr class="border-slate-100 dark:border-slate-800 my-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Current Password</label>
                        <input type="password" name="current_password" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Required only if changing password.</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">New Password</label>
                        <input type="password" name="new_password" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-all shadow-xs mt-4">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Appearance Preferences Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Appearance Mode</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Theme Preference</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Switch between light and dark modes.</p>
                    </div>
                    <button onclick="document.getElementById('theme-toggle').click()" type="button" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-100 text-xs font-semibold rounded-xl transition-all border border-slate-200 dark:border-slate-700 flex items-center">
                        <i data-lucide="sun-moon" class="w-4 h-4 mr-1.5 text-blue-600 dark:text-blue-400"></i> Toggle Mode
                    </button>
                </div>
            </div>
        </div>

        <!-- Profile Information (Read-Only) -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Profile Information</h3>
                    <span class="text-xs text-slate-400 dark:text-slate-500">Read-only view</span>
                </div>
                
                <div class="flex items-center mb-6">
                    <div class="bg-blue-600 text-white font-bold rounded-full w-16 h-16 flex items-center justify-center mr-4 text-xl shrink-0 shadow-sm">
                        {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $member->full_name }}</h4>
                        <div class="mt-1 space-x-1">
                            @foreach($member->positions as $pos)
                                <span class="inline-block bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-xs px-2.5 py-1 rounded-md border border-blue-200/50 dark:border-blue-800/40">{{ $pos->position_name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div><span class="text-slate-500 dark:text-slate-400">First Name</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->first_name }}</p></div>
                    <div><span class="text-slate-500 dark:text-slate-400">Middle Name</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->middle_name ?? '—' }}</p></div>
                    <div><span class="text-slate-500 dark:text-slate-400">Last Name</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->last_name }}</p></div>
                    <div><span class="text-slate-500 dark:text-slate-400">Birth Date</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->birth_date ? $member->birth_date->format('F d, Y') : '—' }}</p></div>
                    <div><span class="text-slate-500 dark:text-slate-400">Gender</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->gender->value ?? $member->gender }}</p></div>
                    <div><span class="text-slate-500 dark:text-slate-400">Contact Number</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->contact_number ?? '—' }}</p></div>
                    <div class="md:col-span-2"><span class="text-slate-500 dark:text-slate-400">Complete Address</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->complete_address ?? '—' }}</p></div>
                    <div><span class="text-slate-500 dark:text-slate-400">Email</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->email_address ?? '—' }}</p></div>
                    <div><span class="text-slate-500 dark:text-slate-400">School Attended</span><p class="font-medium text-slate-800 dark:text-slate-200 mt-0.5">{{ $member->school_attended ?? '—' }}</p></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@endsection
