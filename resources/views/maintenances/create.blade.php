<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Fleet health</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                {{ __('Schedule Maintenance') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="surface">
            <div class="p-6">
                <form action="{{ route('maintenances.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Truck -->
                        <div>
                            <label for="truck_id" class="block text-sm font-medium text-gray-700">Truck</label>
                            <select name="truck_id" id="truck_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select a Truck</option>
                                @foreach($trucks as $truck)
                                    <option value="{{ $truck->id }}" {{ old('truck_id') == $truck->id ? 'selected' : '' }}>
                                        {{ $truck->registration_number }} ({{ $truck->model }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Maintenance Description</label>
                            <input type="text" name="description" id="description" value="{{ old('description') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                placeholder="e.g., Oil Change, Brake Inspection">
                        </div>

                        <!-- Target Mileage -->
                        <div>
                            <label for="target_mileage" class="block text-sm font-medium text-gray-700">Target Mileage (km)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" name="target_mileage" id="target_mileage" value="{{ old('target_mileage') }}" required
                                    class="block w-full rounded-md border-gray-300 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">km</span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 italic">Maintenance will be flagged when the truck reaches this mileage.</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3 border-t border-slate-100 pt-6">
                        <a href="{{ route('maintenances.index') }}" class="btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Schedule Maintenance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
