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
                @if(Auth::user()->isAdmin() || (Auth::user()->isDriver() && Auth::user()->driver?->id === $delivery->driver_id))
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
                        <span class="text-sm text-gray-500">
                            {{ $delivery->status === \App\Models\Delivery::STATUS_DELIVERED ? 'Actual Arrival' : 'Estimated Arrival' }}
                        </span>
                        <span class="text-sm font-bold {{ $delivery->arrival_date ? 'text-gray-900' : 'text-gray-400' }}">
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

        <!-- Fuel Monitoring Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i data-lucide="fuel" class="w-4 h-4 text-indigo-600"></i>
                    Fuel Efficiency Monitoring
                </h3>
                @if($delivery->fuel_status)
                    @php
                        $statusColors = [
                            'normal' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'warning' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'critical' => 'bg-red-100 text-red-700 border-red-200',
                        ];
                        $statusColor = $statusColors[$delivery->fuel_status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                    @endphp
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $statusColor }}">
                        {{ $delivery->fuel_status }} efficiency
                    </span>
                @endif
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Distance</p>
                        <p class="text-xl font-extrabold text-gray-900">{{ number_format($delivery->distance_km, 1) }} <span class="text-sm font-medium text-gray-400">km</span></p>
                    </div>
                    
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Expected Fuel</p>
                        <p class="text-xl font-extrabold text-gray-900">{{ number_format($delivery->expected_fuel, 2) }} <span class="text-sm font-medium text-gray-400">Liters</span></p>
                        <p class="text-[10px] font-bold text-gray-400 italic">Based on {{ $delivery->truck->average_consumption }} L/100km</p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Actual Fuel</p>
                        @if($delivery->actual_fuel !== null)
                            <p class="text-xl font-extrabold text-gray-900">{{ number_format($delivery->actual_fuel, 2) }} <span class="text-sm font-medium text-gray-400">Liters</span></p>
                            @if($delivery->fuel_cost)
                                <p class="text-[10px] font-bold text-gray-400">Cost: {{ number_format($delivery->fuel_cost, 2) }} DH</p>
                            @endif
                        @else
                            <p class="text-xl font-extrabold text-gray-300">Not recorded</p>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Difference</p>
                        @if($delivery->fuel_difference !== null)
                            <p class="text-xl font-extrabold text-gray-900">{{ number_format($delivery->fuel_difference, 2) }} <span class="text-sm font-medium text-gray-400">Liters</span></p>
                        @else
                            <p class="text-xl font-extrabold text-gray-300">--</p>
                        @endif
                    </div>
                </div>

                @if($delivery->status !== 'delivered' && Auth::user()->isDriver())
                    <div class="mt-6 p-4 bg-indigo-50 rounded-xl border border-indigo-100 flex items-start gap-3">
                        <i data-lucide="info" class="w-5 h-5 text-indigo-500 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-bold text-indigo-950">Driver Note</p>
                            <p class="text-xs font-medium text-indigo-700">Please record the actual fuel consumption once the delivery is marked as **Delivered** in the edit screen.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
