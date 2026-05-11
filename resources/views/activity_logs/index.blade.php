<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Audit trail</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                {{ __('System Activity Logs') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="surface p-6">
            <form action="{{ route('activity-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label for="user_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">User</label>
                    <select name="user_id" id="user_id" class="w-full rounded-lg border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="target_type" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Entity</label>
                    <select name="target_type" id="target_type" class="w-full rounded-lg border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Entities</option>
                        @foreach($targetTypes as $type)
                            <option value="{{ $type }}" {{ request('target_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                        class="w-full rounded-lg border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="date_to" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">To</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                        class="w-full rounded-lg border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        Filter
                    </button>
                    <a href="{{ route('activity-logs.index') }}" class="btn-secondary">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Timeline View -->
        <div class="surface">
            <div class="p-6">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($logs as $log)
                        <li>
                            <div class="relative pb-8">
                                @if (!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-100" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        @php
                                            $iconConfigs = [
                                                'login' => ['icon' => 'log-in', 'bg' => 'bg-emerald-500'],
                                                'logout' => ['icon' => 'log-out', 'bg' => 'bg-amber-500'],
                                                'delivery_created' => ['icon' => 'package', 'bg' => 'bg-indigo-500'],
                                                'truck_created' => ['icon' => 'truck', 'bg' => 'bg-blue-500'],
                                                'driver_created' => ['icon' => 'user-plus', 'bg' => 'bg-purple-500'],
                                                'user_created' => ['icon' => 'shield', 'bg' => 'bg-rose-500'],
                                                'deleted' => ['icon' => 'trash-2', 'bg' => 'bg-red-500'],
                                            ];
                                            
                                            $config = $iconConfigs[$log->action] ?? ['icon' => 'activity', 'bg' => 'bg-gray-400'];
                                            if (str_contains($log->action, 'deleted')) $config = $iconConfigs['deleted'];
                                        @endphp
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ $config['bg'] }}">
                                            <i data-lucide="{{ $config['icon'] }}" class="w-4 h-4 text-white"></i>
                                        </span>
                                    </div>
                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500">
                                                <span class="font-bold text-gray-900">{{ $log->user ? $log->user->name : 'System' }}</span>
                                                {{ $log->description }}
                                                @if($log->target_type)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 ml-1">
                                                    {{ $log->target_type }} #{{ $log->target_id }}
                                                </span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="whitespace-nowrap text-right text-xs text-gray-400">
                                            <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                            <div class="mt-0.5 text-[10px]">{{ $log->ip_address }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @empty
                        <div class="text-center py-12">
                            <i data-lucide="info" class="w-12 h-12 text-gray-200 mx-auto mb-4"></i>
                            <p class="text-gray-400">No activity logs found matching your filters.</p>
                        </div>
                        @endforelse
                    </ul>
                </div>

                <div class="mt-12">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
