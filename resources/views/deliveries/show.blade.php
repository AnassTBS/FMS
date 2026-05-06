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

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Trip Information -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Trip Information</h3>
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Route</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $delivery->origin }} → {{ $delivery->destination }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                    ];
                                    $class = $statusClasses[$delivery->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                    {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Departure Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $delivery->departure_date->format('F d, Y - H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Arrival Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $delivery->arrival_date ? $delivery->arrival_date->format('F d, Y - H:i') : 'Not arrived yet' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Assignment Information -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Assignments</h3>
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Truck Assigned</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <p class="font-semibold">{{ $delivery->truck->registration_number }}</p>
                                <p class="text-gray-500 text-xs">{{ $delivery->truck->model }}</p>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Driver Assigned</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $delivery->driver->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900 text-xs">{{ $delivery->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
