<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-950">
                {{ __('Driver Profile') }}: {{ $driver->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('drivers.edit', $driver) }}" class="btn-secondary">
                    Edit
                </a>
                <a href="{{ route('drivers.index') }}" class="btn-secondary">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="md:col-span-1 space-y-6">
                <div class="surface p-6 text-center">
                    <div class="mx-auto w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="user" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $driver->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $driver->license_number }}</p>
                    
                    @php
                        $statusClasses = [
                            'available' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'busy' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'inactive' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $class = $statusClasses[$driver->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                    @endphp
                    <span class="status-badge {{ $class }} justify-center mx-auto">
                        <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span>
                        {{ ucfirst($driver->status) }}
                    </span>
                </div>

                <div class="surface p-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Contact Details</h4>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </div>
                            <span class="text-sm font-medium text-slate-700">{{ $driver->phone }}</span>
                        </div>
                        @if($driver->user)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <span class="text-sm font-medium text-slate-700">{{ $driver->user->email }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats and History -->
            <div class="md:col-span-2 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="surface p-6">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Deliveries</p>
                        <p class="text-3xl font-extrabold text-slate-900">{{ $driver->deliveries->count() }}</p>
                    </div>
                    <div class="surface p-6">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Success Rate</p>
                        <p class="text-3xl font-extrabold text-emerald-600">100%</p>
                    </div>
                </div>

                <div class="surface overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-950 flex items-center gap-2">
                            <i data-lucide="package" class="w-4 h-4 text-indigo-600"></i>
                            Recent Delivery History
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Route</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($driver->deliveries()->latest()->take(5)->get() as $delivery)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-900">{{ $delivery->origin }} → {{ $delivery->destination }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                        {{ $delivery->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs font-bold text-indigo-600 capitalize">{{ str_replace('_', ' ', $delivery->status) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-sm text-slate-400 italic">No delivery records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
