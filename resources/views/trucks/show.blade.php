<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-950">
                {{ __('Truck Details') }}: {{ $truck->registration_number }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('trucks.edit', $truck) }}" class="btn-secondary">
                    Edit
                </a>
                <a href="{{ route('trucks.index') }}" class="btn-secondary">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Asset Card -->
            <div class="md:col-span-1 space-y-6">
                <div class="surface p-6 text-center">
                    <div class="mx-auto w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="truck" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $truck->registration_number }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $truck->model }}</p>
                    
                    @php
                        $statusClasses = [
                            'available' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'on_delivery' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'maintenance' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $class = $statusClasses[$truck->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                    @endphp
                    <span class="status-badge {{ $class }} justify-center mx-auto">
                        <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span>
                        {{ ucfirst(str_replace('_', ' ', $truck->status)) }}
                    </span>
                </div>

                <div class="surface p-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Specifications</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-500">Capacity</span>
                            <span class="text-sm font-bold text-slate-900">{{ number_format($truck->capacity) }} kg</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-500">Fleet ID</span>
                            <span class="text-sm font-medium text-slate-700">#{{ $truck->id }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Operational History -->
            <div class="md:col-span-2 space-y-6">
                <!-- Delivery History -->
                <div class="surface overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-950 flex items-center gap-2">
                            <i data-lucide="package" class="w-4 h-4 text-indigo-600"></i>
                            Recent Deliveries
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Route</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($truck->deliveries as $delivery)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-900">{{ $delivery->origin }} → {{ $delivery->destination }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs font-bold text-indigo-600 capitalize">{{ str_replace('_', ' ', $delivery->status) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                        {{ $delivery->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-slate-400 italic">No delivery history</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Maintenance & Fuel -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="surface p-6">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i data-lucide="wrench" class="w-4 h-4 text-rose-500"></i>
                            Maintenance
                        </h4>
                        <div class="space-y-3">
                            @forelse($truck->maintenances as $maintenance)
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-slate-700">{{ $maintenance->description }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $maintenance->created_at->format('M d') }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">No records</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="surface p-6">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i data-lucide="fuel" class="w-4 h-4 text-amber-500"></i>
                            Fuel Entries
                        </h4>
                        <div class="space-y-3">
                            @forelse($truck->fuelEntries as $fuel)
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900">{{ $fuel->liters }} L</span>
                                    <span class="text-[10px] text-slate-400">{{ $fuel->created_at->format('M d') }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">No records</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
