<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">New operation</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                {{ __('Create New Delivery') }}
            </h2>
        </div>
    </x-slot>

    <div class="surface">
        <div class="p-6">
            <form action="{{ route('deliveries.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Truck Selection -->
                    <div>
                        <label for="truck_id" class="block text-sm font-medium text-gray-700">Truck</label>
                        <select name="truck_id" id="truck_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="">Select a truck</option>
                            @foreach($trucks as $truck)
                                <option value="{{ $truck->id }}" {{ old('truck_id') == $truck->id ? 'selected' : '' }}>
                                    {{ $truck->registration_number }} ({{ $truck->model }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Driver Selection -->
                    <div>
                        <label for="driver_id" class="block text-sm font-medium text-gray-700">Driver</label>
                        <select name="driver_id" id="driver_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="">Select a driver</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Origin -->
                    <div>
                        <label for="origin" class="block text-sm font-medium text-gray-700">Origin</label>
                        <input type="text" name="origin" id="origin" value="{{ old('origin') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <!-- Destination -->
                    <div>
                        <label for="destination" class="block text-sm font-medium text-gray-700">Destination</label>
                        <input type="text" name="destination" id="destination" value="{{ old('destination') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <input type="hidden" name="status" value="{{ \App\Models\Delivery::STATUS_ASSIGNED }}">

                    <!-- Departure Date -->
                    <div>
                        <label for="departure_date" class="block text-sm font-medium text-gray-700">Departure Date & Time</label>
                        <input type="datetime-local" name="departure_date" id="departure_date" value="{{ old('departure_date') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <!-- Arrival Date -->
                    <div>
                        <label for="arrival_date" class="block text-sm font-medium text-gray-700">Arrival Date & Time (Required if Delivered)</label>
                        <input type="datetime-local" name="arrival_date" id="arrival_date" value="{{ old('arrival_date') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Distance -->
                    <div>
                        <label for="distance_km" class="block text-sm font-medium text-gray-700">Route Distance (km)</label>
                        <input type="number" step="0.1" name="distance_km" id="distance_km" value="{{ old('distance_km') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>

                    <div class="md:col-span-2 rounded-lg border border-indigo-100 bg-indigo-50/50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-700">Expected Fuel Preview</p>
                        <p class="mt-1 text-sm text-slate-700">
                            Avg Consumption:
                            <span id="avg_consumption_value" class="font-bold text-slate-900">--</span>
                            L/100km
                        </p>
                        <p class="text-sm text-slate-700">
                            Expected Fuel:
                            <span id="expected_fuel_value" class="font-bold text-slate-900">--</span>
                            L
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3 border-t border-slate-100 pt-6">
                    <a href="{{ route('deliveries.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Save Delivery
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const arrivalInput = document.getElementById('arrival_date');
            const truckSelect = document.getElementById('truck_id');
            const distanceInput = document.getElementById('distance_km');
            const avgConsumptionValue = document.getElementById('avg_consumption_value');
            const expectedFuelValue = document.getElementById('expected_fuel_value');

            if (truckSelect) {
                Array.from(truckSelect.options).forEach(option => {
                    @foreach($trucks as $truck)
                    if (option.value === '{{ $truck->id }}') {
                        option.dataset.avgConsumption = '{{ $truck->average_consumption }}';
                    }
                    @endforeach
                });
            }

            const updateExpectedFuel = () => {
                const selected = truckSelect?.selectedOptions?.[0];
                const avg = selected?.dataset?.avgConsumption ? parseFloat(selected.dataset.avgConsumption) : null;
                const distance = distanceInput?.value ? parseFloat(distanceInput.value) : null;

                avgConsumptionValue.textContent = avg !== null && !Number.isNaN(avg) ? avg.toFixed(2) : '--';

                if (avg !== null && distance !== null && distance > 0 && !Number.isNaN(avg) && !Number.isNaN(distance)) {
                    expectedFuelValue.textContent = ((avg * distance) / 100).toFixed(2);
                } else {
                    expectedFuelValue.textContent = '--';
                }
            };

            truckSelect?.addEventListener('change', updateExpectedFuel);
            distanceInput?.addEventListener('input', updateExpectedFuel);
            updateExpectedFuel();

            if (statusSelect && arrivalInput) {
                statusSelect.addEventListener('change', function() {
                    if (this.value === 'delivered' && !arrivalInput.value) {
                        const now = new Date();
                        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                        arrivalInput.value = now.toISOString().slice(0, 16);
                    }
                });

                arrivalInput.addEventListener('change', function() {
                    if (this.value) {
                        statusSelect.value = 'delivered';
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
