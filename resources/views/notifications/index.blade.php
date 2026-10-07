@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Notifications</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage and send notifications to specific roles.</p>
        </div>
        @if($canCreate)
        <button onclick="document.getElementById('addNotificationModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-xs inline-flex items-center justify-center self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Notification
        </button>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-4 py-3 rounded-xl text-sm shadow-xs">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-4 py-3 rounded-xl text-sm shadow-xs">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Notifications List -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        @if($notifications->isEmpty())
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl mb-4 border border-slate-200/60 dark:border-slate-700">
                    <i data-lucide="bell" class="w-8 h-8 text-slate-400 dark:text-slate-500"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-1">No notifications found</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Get started by creating a new notification.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50/80 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-medium border-b border-slate-200/80 dark:border-slate-800 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 w-1/4">Title</th>
                            <th class="px-6 py-4 w-1/3">Message</th>
                            <th class="px-6 py-4">Target Roles</th>
                            <th class="px-6 py-4">Created By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @foreach($notifications as $notification)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-slate-400">
                                {{ $notification->created_at->format('M d, Y') }}<br>
                                <span class="text-xs text-slate-400 dark:text-slate-500">{{ $notification->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">{{ $notification->title }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 truncate max-w-xs" title="{{ $notification->message }}">
                                {{ \Illuminate\Support\Str::limit($notification->message, 80) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @php
                                        $targetIds = is_array($notification->target_roles) ? $notification->target_roles : [];
                                        $targetPositions = collect($positions)->whereIn('id', $targetIds);
                                    @endphp
                                    @forelse($targetPositions as $pos)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200/50 dark:border-blue-800/40">
                                            {{ $pos->position_name }}
                                        </span>
                                    @empty
                                        <span class="text-slate-400 dark:text-slate-500 text-xs italic">Unknown</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 whitespace-nowrap">
                                {{ $notification->creator ? $notification->creator->full_name : 'System' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Add Notification Modal -->
@if($canCreate)
<div id="addNotificationModal" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-4xl overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Add New Notification</h3>
            <button onclick="document.getElementById('addNotificationModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('notifications.store') }}" method="POST">
            @csrf
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column: Content -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Title</label>
                        <input type="text" name="title" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Notification Title">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Message</label>
                        <textarea name="message" required rows="6" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Type the notification message here..."></textarea>
                    </div>
                </div>
                
                <!-- Right Column: Roles -->
                <div class="flex flex-col h-full">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Send To (Roles)</label>
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-xl border border-slate-200 dark:border-slate-700 flex-1 overflow-y-auto space-y-2.5 min-h-[200px]">
                        @foreach($positions as $position)
                        <label class="flex items-center cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 p-2 rounded-lg transition-colors">
                            <input type="checkbox" name="target_roles[]" value="{{ $position->id }}" class="rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-slate-700 dark:text-slate-200 font-medium">{{ $position->position_name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">Select at least one role to receive this notification.</p>
                </div>
            </div>
            <div class="p-6 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('addNotificationModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-xs">Send Notification</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
