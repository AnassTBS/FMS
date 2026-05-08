<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Fleet health</p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                    {{ __('Maintenance Schedule') }}
                </h2>
            </div>
            <a href="{{ route('maintenances.create') }}" class="btn-primary">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Schedule Maintenance
            </a>
        </div>
    </x-slot>

    <div class="surface">
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Truck</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Target Mileage</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($maintenances as $maintenance)
                            <tr class="hover:bg-gray-50/50 transition-colors {{ $maintenance->is_completed ? 'opacity-60' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="rounded-lg bg-blue-50 p-1.5 text-blue-600">
                                            <i data-lucide="wrench" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-900">{{ $maintenance->truck->registration_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $maintenance->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($maintenance->target_mileage) }} km
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($maintenance->is_completed)
                                        <span class="status-badge bg-emerald-50 text-emerald-700 border-emerald-200">
                                            Completed
                                        </span>
                                    @else
                                        <span class="status-badge bg-amber-50 text-amber-700 border-amber-200">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        @if(!$maintenance->is_completed)
                                        <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_completed" value="1">
                                            <button type="submit" class="action-icon !text-indigo-600 hover:!text-indigo-800" title="Mark as Completed">
                                                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon hover:!text-red-600" title="Delete">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm font-bold text-slate-500">
                                    No maintenance records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $maintenances->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
