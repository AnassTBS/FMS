<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Fleet assets</p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                    {{ __('Truck Management') }}
                </h2>
            </div>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('trucks.create') }}" class="btn-primary">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Add New Truck
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Truck Details</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Capacity</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Deliveries</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($trucks as $truck)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="rounded-xl bg-indigo-50 p-2 text-indigo-600">
                                            <i data-lucide="truck" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $truck->registration_number }}</div>
                                            <div class="text-xs text-gray-500">{{ $truck->model }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="font-bold text-gray-900">{{ number_format($truck->capacity) }}</span> kg
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center gap-1 font-bold text-slate-700">
                                        <i data-lucide="package" class="w-3.5 h-3.5 text-slate-400"></i>
                                        {{ $truck->deliveries_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'available' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'on_delivery' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'reserved' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'busy' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'maintenance' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        ];
                                        $class = $statusClasses[$truck->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span>
                                        {{ ucfirst(str_replace('_', ' ', $truck->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center gap-3">
                                        <a href="{{ route('trucks.show', $truck) }}" class="action-icon" title="View Details">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                        <a href="{{ route('trucks.edit', $truck) }}" class="action-icon hover:!text-amber-600" title="Edit">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </a>
                                        <form action="{{ route('trucks.destroy', $truck) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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
                                <td colspan="5" class="px-6 py-12 text-center text-sm font-bold text-slate-500">
                                    No trucks found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $trucks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
