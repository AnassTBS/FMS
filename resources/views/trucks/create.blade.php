<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Fleet assets</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                {{ __('Add New Truck') }}
            </h2>
        </div>
    </x-slot>

    <div class="surface">
        <div class="p-6">
            <form action="{{ route('trucks.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Registration Number -->
                    <div>
                        <label for="registration_number" class="block text-sm font-bold text-slate-700">Registration Number</label>
                        <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number') }}" class="mt-1 block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g. TR-1234-A" required>
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="block text-sm font-bold text-slate-700">Truck Model</label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}" class="mt-1 block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g. Volvo FH16" required>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block text-sm font-bold text-slate-700">Capacity (kg)</label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity') }}" class="mt-1 block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g. 25000" required>
                    </div>

                    <!-- Average Consumption -->
                    <div>
                        <label for="average_consumption" class="block text-sm font-bold text-slate-700">Expected Consumption (L/100km)</label>
                        <input type="number" step="0.01" name="average_consumption" id="average_consumption" value="{{ old('average_consumption', 35.00) }}" class="mt-1 block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-bold text-slate-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3 border-t border-slate-100 pt-6">
                    <a href="{{ route('trucks.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Save Truck
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
