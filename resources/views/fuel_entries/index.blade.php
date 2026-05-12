<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Fuel monitoring system</p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                    {{ __('Fuel tracking and monitoring') }}
                </h2>
            </div>
            <a href="{{ route('fuel-entries.create') }}" class="btn-primary">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Add Fuel Entry
            </a>
        </div>
    </x-slot>
    
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="surface p-4 flex items-center gap-4">
            <div class="rounded-xl bg-amber-50 p-3 text-amber-600">
                <i data-lucide="droplet" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Fuel Used</p>
                <p class="text-xl font-extrabold text-slate-900">{{ number_format($stats['total_fuel'], 1) }} L</p>
            </div>
        </div>
        <div class="surface p-4 flex items-center gap-4">
            <div class="rounded-xl bg-emerald-50 p-3 text-emerald-600">
                <i data-lucide="dollar-sign" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Fuel Cost</p>
                <p class="text-xl font-extrabold text-slate-900">${{ number_format($stats['total_cost'], 2) }}</p>
            </div>
        </div>
        <div class="surface p-4 flex items-center gap-4">
            <div class="rounded-xl bg-indigo-50 p-3 text-indigo-600">
                <i data-lucide="trending-up" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Avg Price/L</p>
                <p class="text-xl font-extrabold text-slate-900">${{ number_format($stats['avg_price'], 2) }}</p>
            </div>
        </div>
        <div class="surface p-4 flex items-center gap-4">
            <div class="rounded-xl bg-blue-50 p-3 text-blue-600">
                <i data-lucide="gauge" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Avg Consumption</p>
                <p class="text-xl font-extrabold text-slate-900">{{ number_format($stats['avg_consumption'], 1) }} <span class="text-xs font-bold text-slate-500">L/100km</span></p>
            </div>
        </div>
    </div>

    <div class="surface">
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Truck & Station</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fuel Details</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mileage</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Consumption</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            @if(auth()->user()->isAdmin())
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($fuelEntries as $entry)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="rounded-lg bg-slate-50 p-1.5 text-slate-600">
                                            <i data-lucide="truck" class="w-4 h-4"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900">{{ $entry->truck->registration_number }}</span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $entry->fuel_station ?? 'Private Station' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                    {{ $entry->date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900">{{ number_format($entry->liters, 2) }} L</span>
                                        <span class="text-xs font-bold text-emerald-600">${{ number_format($entry->amount, 2) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900">{{ number_format($entry->mileage) }} <span class="text-xs text-slate-400">km</span></span>
                                        @if($entry->distance_traveled)
                                            <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">+{{ number_format($entry->distance_traveled) }} km traveled</span>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-300 uppercase tracking-wider">First entry</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($entry->real_consumption)
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900">{{ number_format($entry->real_consumption, 1) }} <span class="text-xs text-slate-400">L/100km</span></span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Exp: {{ number_format($entry->truck->average_consumption, 1) }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-slate-300 italic">No data</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'normal' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'critical' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        ];
                                        $class = $statusClasses[$entry->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span>
                                        {{ ucfirst($entry->status) }}
                                    </span>
                                </td>
                                @if(auth()->user()->isAdmin())
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('fuel-entries.destroy', $entry) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon hover:!text-red-600" title="Delete">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm font-bold text-slate-500">
                                    No fuel entries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $fuelEntries->links() }}
            </div>
        </div>
    </div>

    <div class="surface mt-6">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-bold text-gray-900">Delivery Fuel Monitoring Logs</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Delivery</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Truck Avg</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Distance</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Expected Fuel</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actual Fuel</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Difference</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fuel Cost</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($deliveryFuelLogs as $delivery)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900">
                                #{{ $delivery->id }} - {{ $delivery->origin }} → {{ $delivery->destination }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ number_format($delivery->truck?->average_consumption ?? 0, 2) }} L/100km</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ number_format($delivery->distance_km ?? 0, 1) }} km</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ number_format($delivery->expected_fuel ?? 0, 2) }} L</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $delivery->actual_fuel !== null ? number_format($delivery->actual_fuel, 2) . ' L' : '--' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $delivery->fuel_difference !== null ? number_format($delivery->fuel_difference, 2) . ' L' : '--' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $delivery->fuel_cost !== null ? number_format($delivery->fuel_cost, 2) . ' DH' : '--' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'normal' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'critical' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                    $class = $statusClasses[$delivery->fuel_status] ?? 'bg-gray-50 text-gray-500 border-gray-200';
                                @endphp
                                <span class="status-badge {{ $class }}">
                                    {{ $delivery->fuel_status ? strtoupper($delivery->fuel_status) : 'PENDING' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm font-bold text-slate-500">
                                No delivery fuel monitoring data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-6 py-4">
            {{ $deliveryFuelLogs->links() }}
        </div>
    </div>
</x-app-layout>
