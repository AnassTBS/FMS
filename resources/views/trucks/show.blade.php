<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Truck Details') }}: {{ $truck->registration_number }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('trucks.edit', $truck) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Edit
                </a>
                <a href="{{ route('trucks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Truck Info -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Technical Specifications</h3>
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $truck->registration_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Model</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $truck->model }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ number_format($truck->capacity) }} kg</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                            <dd class="mt-1 text-sm">
                                @php
                                    $statusClasses = [
                                        'available' => 'bg-green-100 text-green-800',
                                        'in_use' => 'bg-blue-100 text-blue-800',
                                        'maintenance' => 'bg-red-100 text-red-800',
                                    ];
                                    $class = $statusClasses[$truck->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                    {{ ucfirst(str_replace('_', ' ', $truck->status)) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Delivery History Summary -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Delivery Statistics</h3>
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Deliveries</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $truck->deliveries->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Latest Delivery</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($latest = $truck->deliveries()->latest()->first())
                                    {{ $latest->origin }} → {{ $latest->destination }} ({{ $latest->created_at->format('M d, Y') }})
                                @else
                                    <span class="text-gray-400 italic">No deliveries recorded</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
