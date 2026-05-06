<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Delivery') }} #{{ $delivery->id }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('deliveries.update', $delivery) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Truck Selection -->
                    <div>
                        <label for="truck_id" class="block text-sm font-medium text-gray-700">Truck</label>
                        <select name="truck_id" id="truck_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            @foreach($trucks as $truck)
                                <option value="{{ $truck->id }}" {{ old('truck_id', $delivery->truck_id) == $truck->id ? 'selected' : '' }}>
                                    {{ $truck->registration_number }} ({{ $truck->model }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Driver Selection -->
                    <div>
                        <label for="driver_id" class="block text-sm font-medium text-gray-700">Driver</label>
                        <select name="driver_id" id="driver_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver_id', $delivery->driver_id) == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Origin -->
                    <div>
                        <label for="origin" class="block text-sm font-medium text-gray-700">Origin</label>
                        <input type="text" name="origin" id="origin" value="{{ old('origin', $delivery->origin) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <!-- Destination -->
                    <div>
                        <label for="destination" class="block text-sm font-medium text-gray-700">Destination</label>
                        <input type="text" name="destination" id="destination" value="{{ old('destination', $delivery->destination) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="pending" {{ old('status', $delivery->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status', $delivery->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $delivery->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Departure Date -->
                    <div>
                        <label for="departure_date" class="block text-sm font-medium text-gray-700">Departure Date & Time</label>
                        <input type="datetime-local" name="departure_date" id="departure_date" value="{{ old('departure_date', $delivery->departure_date->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <!-- Arrival Date -->
                    <div>
                        <label for="arrival_date" class="block text-sm font-medium text-gray-700">Arrival Date & Time (Required if Completed)</label>
                        <input type="datetime-local" name="arrival_date" id="arrival_date" value="{{ old('arrival_date', $delivery->arrival_date ? $delivery->arrival_date->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('deliveries.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Update Delivery
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
