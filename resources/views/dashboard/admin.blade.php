<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-bold text-indigo-600 mb-2">Users Management</h3>
            <p class="text-gray-600 mb-4">Total Users: {{ \App\Models\User::count() }}</p>
            <a href="#" class="text-sm font-medium text-indigo-600 hover:underline">Manage Users →</a>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-bold text-green-600 mb-2">Fleet Status</h3>
            <p class="text-gray-600 mb-4">Active Trucks: {{ \App\Models\Truck::count() }}</p>
            <a href="#" class="text-sm font-medium text-green-600 hover:underline">View Fleet →</a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-bold text-blue-600 mb-2">Deliveries</h3>
            <p class="text-gray-600 mb-4">Total Deliveries: {{ \App\Models\Delivery::count() }}</p>
            <a href="{{ route('deliveries.index') }}" class="text-sm font-medium text-blue-600 hover:underline">Manage Deliveries →</a>
        </div>
    </div>
</x-app-layout>
