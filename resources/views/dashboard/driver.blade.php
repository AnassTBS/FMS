<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Driver Portal') }}
            </h2>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Driver Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-indigo-50 p-3 rounded-lg text-indigo-600">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">My Deliveries</p>
                    <p class="text-2xl font-black text-gray-900">{{ $my_deliveries_count }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-emerald-50 p-3 rounded-lg text-emerald-600">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Completed</p>
                    <p class="text-2xl font-black text-gray-900">{{ $completed_deliveries_count }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-blue-50 p-3 rounded-lg text-blue-600">
                    <i data-lucide="activity" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status</p>
                    <p class="text-lg font-bold text-gray-900">{{ Auth::user()->driver->status ?? 'Available' }}</p>
                </div>
            </div>
        </div>

        <!-- Active Tasks -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-indigo-600"></i>
                    Current Assignments
                </h3>
            </div>
            <div class="p-6">
                @forelse($active_deliveries as $delivery)
                <div class="flex flex-col md:flex-row justify-between items-center p-4 bg-gray-50 rounded-xl border border-gray-100 gap-4 mb-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                            <i data-lucide="truck" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $delivery->origin }} → {{ $delivery->destination }}</p>
                            <p class="text-xs text-gray-500">Scheduled: {{ $delivery->departure_date->format('M d, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('deliveries.show', $delivery) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                            View Route
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="coffee" class="w-8 h-8 text-gray-300"></i>
                    </div>
                    <p class="text-gray-500 font-medium">No active deliveries assigned yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
