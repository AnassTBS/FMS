<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Operations Dashboard (Dispatcher)') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-bold text-blue-600 mb-2">Active Deliveries</h3>
            <p class="text-gray-600 mb-4">In Progress: {{ \App\Models\Delivery::where('status', 'in_progress')->count() }}</p>
            <a href="{{ route('deliveries.index') }}" class="text-sm font-medium text-blue-600 hover:underline">Assign Trucks & Drivers →</a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-bold text-yellow-600 mb-2">Pending Requests</h3>
            <p class="text-gray-600 mb-4">Awaiting Dispatch: {{ \App\Models\Delivery::where('status', 'pending')->count() }}</p>
            <a href="{{ route('deliveries.index') }}" class="text-sm font-medium text-yellow-600 hover:underline">Dispatch Now →</a>
        </div>
    </div>
</x-app-layout>
