<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Delivery record</p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                    {{ __('Delivery Details') }} #{{ $delivery->id }}
                </h2>
            </div>
            <div class="flex gap-2">
                @if(Auth::user()->isAdmin() || Auth::user()->isDispatcher() || (Auth::user()->isDriver() && Auth::user()->driver?->id === $delivery->driver_id))
                    <a href="{{ route('deliveries.edit', $delivery) }}" class="btn-secondary">
                        Edit
                    </a>
                @endif
                <a href="{{ route('deliveries.index') }}" class="btn-secondary">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-indigo-50 p-3 rounded-lg text-indigo-600">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Status</p>
                    @php
                        $statusClasses = [
                            'assigned' => 'text-amber-600',
                            'in_transit' => 'text-blue-600',
                            'delivered' => 'text-emerald-600',
                        ];
                        $class = $statusClasses[$delivery->status] ?? 'text-gray-600';
                    @endphp
                    <p class="text-lg font-bold {{ $class }}">{{ $delivery->statusLabel() }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-indigo-50 p-3 rounded-lg text-indigo-600">
                    <i data-lucide="map-pin" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Route</p>
                    <p class="flex items-center gap-2 text-sm font-bold text-gray-900">
                        <span>{{ $delivery->origin }}</span>
                        <i data-lucide="arrow-right" class="h-3.5 w-3.5 text-slate-400"></i>
                        <span>{{ $delivery->destination }}</span>
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-indigo-50 p-3 rounded-lg text-indigo-600">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Departure</p>
                    <p class="text-sm font-bold text-gray-900">{{ $delivery->departure_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Assignment Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4 text-indigo-600"></i>
                        Assignments
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-400">
                            <i data-lucide="truck" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Truck Assigned</p>
                            <p class="text-sm font-bold text-gray-900">{{ $delivery->truck->registration_number }}</p>
                            <p class="text-xs text-gray-500">{{ $delivery->truck->model }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-400">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Driver Assigned</p>
                            <p class="text-sm font-bold text-gray-900">{{ $delivery->driver->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $delivery->driver->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="clock" class="w-4 h-4 text-indigo-600"></i>
                        Schedule Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Departure Time</span>
                        <span class="text-sm font-bold text-gray-900">{{ $delivery->departure_date->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Estimated Arrival</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ $delivery->arrival_date ? $delivery->arrival_date->format('M d, Y H:i') : 'Pending' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Created At</span>
                        <span class="text-sm font-medium text-gray-400">{{ $delivery->created_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
