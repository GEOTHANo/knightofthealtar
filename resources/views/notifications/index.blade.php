@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and send notifications to specific roles.</p>
        </div>
        @if($canCreate)
        <button onclick="document.getElementById('addNotificationModal').classList.remove('hidden')" class="bg-[#246b9c] hover:bg-[#1a547b] text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm inline-flex items-center justify-center self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Notification
        </button>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($notifications->isEmpty())
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-50 rounded-full mb-4">
                    <i data-lucide="bell" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No notifications found</h3>
                <p class="text-sm text-gray-500">Get started by creating a new notification.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 w-1/4">Title</th>
                            <th class="px-6 py-4 w-1/3">Message</th>
                            <th class="px-6 py-4">Target Roles</th>
                            <th class="px-6 py-4">Created By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($notifications as $notification)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $notification->created_at->format('M d, Y') }}<br>
                                <span class="text-xs">{{ $notification->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $notification->title }}</td>
                            <td class="px-6 py-4 text-gray-500 truncate max-w-xs" title="{{ $notification->message }}">
                                {{ \Illuminate\Support\Str::limit($notification->message, 80) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        // $notification->target_roles contains position IDs
                                        $targetIds = is_array($notification->target_roles) ? $notification->target_roles : [];
                                        $targetPositions = collect($positions)->whereIn('id', $targetIds);
                                    @endphp
                                    @forelse($targetPositions as $pos)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $pos->position_name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 text-xs italic">Unknown</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
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
<div id="addNotificationModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Add New Notification</h3>
            <button onclick="document.getElementById('addNotificationModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('notifications.store') }}" method="POST">
            @csrf
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column: Content -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none" placeholder="Notification Title">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea name="message" required rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#246b9c] focus:border-[#246b9c] outline-none" placeholder="Type the notification message here..."></textarea>
                    </div>
                </div>
                
                <!-- Right Column: Roles -->
                <div class="flex flex-col h-full">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Send To (Roles)</label>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 flex-1 overflow-y-auto space-y-3 min-h-[200px]">
                        @foreach($positions as $position)
                        <label class="flex items-center cursor-pointer hover:bg-gray-100 p-2 rounded transition-colors">
                            <input type="checkbox" name="target_roles[]" value="{{ $position->id }}" class="rounded border-gray-300 text-[#246b9c] shadow-sm focus:border-[#246b9c] focus:ring focus:ring-[#246b9c] focus:ring-opacity-50">
                            <span class="ml-3 text-sm text-gray-700 font-medium">{{ $position->position_name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Select at least one role to receive this notification.</p>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 bg-gray-50 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('addNotificationModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#246b9c] hover:bg-[#1a547b] rounded-lg transition-colors shadow-sm">Send Notification</button>
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
