<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Update operation</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                {{ __('Edit Delivery') }} #{{ $delivery->id }}
            </h2>
        </div>
    </x-slot>

    <div class="surface">
        <div class="p-6">
            <form action="{{ route('deliveries.update', $delivery) }}" method="POST">
                @csrf
                @method('PUT')

                @if(Auth::user()->isDriver())
                <div class="max-w-md">
                    <label for="status" class="block text-sm font-medium text-gray-700">Delivery Status</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        @foreach(\App\Models\Delivery::statusLabels() as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $delivery->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs font-semibold text-slate-500">You can only update the status for deliveries assigned to you.</p>
                </div>
                @else
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
                            @foreach(\App\Models\Delivery::statusLabels() as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $delivery->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Departure Date -->
                    <div>
                        <label for="departure_date" class="block text-sm font-medium text-gray-700">Departure Date & Time</label>
                        <input type="datetime-local" name="departure_date" id="departure_date" value="{{ old('departure_date', $delivery->departure_date->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <!-- Arrival Date -->
                    <div>
                        <label for="arrival_date" class="block text-sm font-medium text-gray-700">Arrival Date & Time (Required if Delivered)</label>
                        <input type="datetime-local" name="arrival_date" id="arrival_date" value="{{ old('arrival_date', $delivery->arrival_date ? $delivery->arrival_date->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
                @endif

                <div class="mt-8 flex justify-end space-x-3 border-t border-slate-100 pt-6">
                    <a href="{{ route('deliveries.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Update Delivery
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
