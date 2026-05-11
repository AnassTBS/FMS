<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Operations</p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                    {{ __('Delivery List') }}
                </h2>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isDispatcher())
                <a href="{{ route('deliveries.create') }}" class="btn-primary">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Create Delivery
                </a>
            @endif
        </div>
    </x-slot>

    <div class="surface">
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Assigned Fleet</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Route</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Schedule</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($deliveries as $delivery)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-start flex-col gap-1">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="truck" class="w-3.5 h-3.5 text-gray-400"></i>
                                            <span class="text-sm font-semibold text-gray-900">{{ $delivery->truck->registration_number }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="user" class="w-3.5 h-3.5 text-gray-400"></i>
                                            <span class="text-xs text-gray-500">{{ $delivery->driver->full_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900">{{ $delivery->origin }}</span>
                                            <span class="text-xs text-gray-400">Departure</span>
                                        </div>
                                        <i data-lucide="arrow-right" class="w-4 h-4 text-gray-300"></i>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900">{{ $delivery->destination }}</span>
                                            <span class="text-xs text-gray-400">Destination</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'assigned' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'in_transit' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        ];
                                        $class = $statusClasses[$delivery->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span>
                                        {{ $delivery->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                                            <span class="font-medium text-xs">{{ $delivery->departure_date->format('M d, H:i') }}</span>
                                        </div>
                                        @if($delivery->arrival_date)
                                            <div class="flex items-center gap-1.5">
                                                @if($delivery->status === \App\Models\Delivery::STATUS_DELIVERED)
                                                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-500" title="Actual Arrival"></i>
                                                    <span class="font-bold text-xs text-emerald-700">{{ $delivery->arrival_date->format('M d, H:i') }}</span>
                                                @else
                                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-400" title="Estimated Arrival"></i>
                                                    <span class="font-medium text-xs text-blue-600">Est. {{ $delivery->arrival_date->format('M d, H:i') }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5">
                                                <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-300"></i>
                                                <span class="text-xs text-slate-400 font-medium italic">No estimate</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center gap-3">
                                        <a href="{{ route('deliveries.show', $delivery) }}" class="action-icon" title="View Details">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </a>
                                        @if(Auth::user()->isAdmin() || Auth::user()->isDispatcher())
                                        <a href="{{ route('deliveries.edit', $delivery) }}" class="action-icon hover:!text-amber-600" title="Edit">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </a>
                                        <form action="{{ route('deliveries.destroy', $delivery) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon hover:!text-red-600" title="Delete">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm font-bold text-slate-500">
                                    No deliveries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $deliveries->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
