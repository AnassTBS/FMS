<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Fuel monitoring</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                {{ __('New Fuel Log') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="surface">
            <div class="p-6">
                <form action="{{ route('fuel-entries.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Truck -->
                            <div>
                                <label for="truck_id" class="block text-sm font-bold text-slate-700">Truck</label>
                                <select name="truck_id" id="truck_id" required
                                    class="mt-1 block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Select a Truck</option>
                                    @foreach($trucks as $truck)
                                        <option value="{{ $truck->id }}" {{ old('truck_id', $defaultTruckId) == $truck->id ? 'selected' : '' }}>
                                            {{ $truck->registration_number }} ({{ $truck->model }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date -->
                            <div>
                                <label for="date" class="block text-sm font-bold text-slate-700">Date</label>
                                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                                    class="mt-1 block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Liters -->
                            <div>
                                <label for="liters" class="block text-sm font-bold text-slate-700">Liters</label>
                                <div class="mt-1 relative rounded-xl shadow-sm">
                                    <input type="number" step="0.01" name="liters" id="liters" value="{{ old('liters') }}" required
                                        class="block w-full rounded-xl border-slate-200 pr-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0.00">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-bold text-xs uppercase">L</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div>
                                <label for="amount" class="block text-sm font-bold text-slate-700">Total Amount</label>
                                <div class="mt-1 relative rounded-xl shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-bold sm:text-sm">$</span>
                                    </div>
                                    <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" required
                                        class="block w-full rounded-xl border-slate-200 pl-7 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Mileage -->
                        <div>
                            <label for="mileage" class="block text-sm font-bold text-slate-700">Current Odometer (km)</label>
                            <div class="mt-1 relative rounded-xl shadow-sm">
                                <input type="number" name="mileage" id="mileage" value="{{ old('mileage') }}" required
                                    class="block w-full rounded-xl border-slate-200 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Current mileage">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-400 font-bold text-xs uppercase">km</span>
                                </div>
                            </div>
                            <p class="mt-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Calculations will be automated based on last entry</p>
                        </div>

                        <!-- Station -->
                        <div>
                            <label for="fuel_station" class="block text-sm font-bold text-slate-700">Fuel Station (Optional)</label>
                            <input type="text" name="fuel_station" id="fuel_station" value="{{ old('fuel_station') }}"
                                class="mt-1 block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Station name or location">
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-bold text-slate-700">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Any additional details...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3 border-t border-slate-100 pt-6">
                        <a href="{{ route('fuel-entries.index') }}" class="btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Analyze & Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
