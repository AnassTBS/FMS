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

    <div class="surface">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Driver Info -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Information</h3>
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $driver->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">License Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $driver->license_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $driver->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm">
                                @php
                                    $statusClasses = [
                                        'available' => 'bg-green-100 text-green-800',
                                        'busy' => 'bg-blue-100 text-blue-800',
                                        'inactive' => 'bg-red-100 text-red-800',
                                    ];
                                    $class = $statusClasses[$driver->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                    {{ ucfirst($driver->status) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Statistics -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Delivery Statistics</h3>
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Deliveries</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $driver->deliveries->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Latest Route</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($latest = $driver->deliveries()->latest()->first())
                                    {{ $latest->origin }} to {{ $latest->destination }} ({{ $latest->created_at->format('M d, Y') }})
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
