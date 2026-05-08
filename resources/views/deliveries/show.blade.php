<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Delivery Details') }} #{{ $delivery->id }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('deliveries.edit', $delivery) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Edit
                </a>
                <a href="{{ route('deliveries.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
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
                            'pending' => 'text-amber-600',
                            'in_progress' => 'text-blue-600',
                            'completed' => 'text-emerald-600',
                        ];
                        $class = $statusClasses[$delivery->status] ?? 'text-gray-600';
                    @endphp
                    <p class="text-lg font-bold {{ $class }}">{{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-indigo-50 p-3 rounded-lg text-indigo-600">
                    <i data-lucide="map-pin" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Route</p>
                    <p class="text-sm font-bold text-gray-900">{{ $delivery->origin }} → {{ $delivery->destination }}</p>
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
