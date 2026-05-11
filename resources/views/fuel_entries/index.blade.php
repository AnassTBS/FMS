<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Cost tracking</p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                    {{ __('Fuel Consumption Logs') }}
                </h2>
            </div>
            <a href="{{ route('fuel-entries.create') }}" class="btn-primary">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Add Fuel Entry
            </a>
        </div>
    </x-slot>
    
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="surface p-4 flex items-center gap-4">
            <div class="rounded-xl bg-amber-50 p-3 text-amber-600">
                <i data-lucide="droplet" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Fuel</p>
                <p class="text-xl font-extrabold text-slate-900">{{ number_format($fuelEntries->sum('liters'), 1) }} L</p>
            </div>
        </div>
        <div class="surface p-4 flex items-center gap-4">
            <div class="rounded-xl bg-emerald-50 p-3 text-emerald-600">
                <i data-lucide="dollar-sign" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Cost</p>
                <p class="text-xl font-extrabold text-slate-900">${{ number_format($fuelEntries->sum('amount'), 2) }}</p>
            </div>
        </div>
        <div class="surface p-4 flex items-center gap-4">
            <div class="rounded-xl bg-indigo-50 p-3 text-indigo-600">
                <i data-lucide="trending-up" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Avg Price/L</p>
                <p class="text-xl font-extrabold text-slate-900">
                    @if($fuelEntries->sum('liters') > 0)
                        ${{ number_format($fuelEntries->sum('amount') / $fuelEntries->sum('liters'), 2) }}
                    @else
                        $0.00
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="surface">
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Truck</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Liters</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mileage</th>
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
                                        <div class="rounded-lg bg-amber-50 p-1.5 text-amber-600">
                                            <i data-lucide="fuel" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-900">{{ $entry->truck->registration_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $entry->date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ number_format($entry->liters, 2) }} L
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-emerald-600 font-bold">
                                    ${{ number_format($entry->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ number_format($entry->mileage) }} km
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
                                <td colspan="6" class="px-6 py-12 text-center text-sm font-bold text-slate-500">
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
</x-app-layout>
