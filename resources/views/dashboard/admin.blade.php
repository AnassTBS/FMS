<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Logistics Overview') }}
            </h2>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>{{ now()->format('F d, Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Deliveries -->
            <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 to-indigo-700 p-6 rounded-2xl shadow-lg shadow-indigo-200">
                <div class="relative z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider">Total Deliveries</p>
                            <h3 class="text-3xl font-black text-white mt-1">{{ $stats['total_deliveries'] }}</h3>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-md">
                            <i data-lucide="package" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-indigo-100 text-xs font-medium">
                        <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                        <span>System Total</span>
                    </div>
                </div>
                <div class="absolute -right-4 -bottom-4 opacity-10">
                    <i data-lucide="package" class="w-24 h-24 text-white"></i>
                </div>
            </div>

            <!-- Active Deliveries -->
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow-lg shadow-blue-200">
                <div class="relative z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-blue-100 text-xs font-bold uppercase tracking-wider">In Progress</p>
                            <h3 class="text-3xl font-black text-white mt-1">{{ $stats['active_deliveries'] }}</h3>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-md">
                            <i data-lucide="truck" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-blue-100 text-xs font-medium">
                        <span class="w-2 h-2 rounded-full bg-white animate-pulse mr-2"></span>
                        <span>Live Operations</span>
                    </div>
                </div>
            </div>

            <!-- Available Trucks -->
            <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-2xl shadow-lg shadow-emerald-200">
                <div class="relative z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Available Trucks</p>
                            <h3 class="text-3xl font-black text-white mt-1">{{ $stats['available_trucks'] }}</h3>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-md">
                            <i data-lucide="container" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-emerald-100 text-xs font-medium">
                        {{ $stats['trucks_on_delivery'] }} Currently deployed
                    </div>
                </div>
            </div>

            <!-- Completion Rate -->
            <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-amber-600 p-6 rounded-2xl shadow-lg shadow-amber-200">
                <div class="relative z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-amber-100 text-xs font-bold uppercase tracking-wider">Success Rate</p>
                            <h3 class="text-3xl font-black text-white mt-1">{{ $stats['completion_rate'] }}%</h3>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-md">
                            <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-white/20 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-white h-full" style="width: {{ $stats['completion_rate'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Delivery Status -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-indigo-600"></i>
                    Delivery Status Distribution
                </h3>
                <div id="statusChart" class="min-h-[300px]"></div>
            </div>

            <!-- Fleet Utilization -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-indigo-600"></i>
                    Fleet Utilization
                </h3>
                <div id="utilizationChart" class="min-h-[300px]"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Deliveries (Left 2/3) -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="list" class="w-4 h-4 text-indigo-600"></i>
                        Recent Deliveries
                    </h3>
                    <a href="{{ route('deliveries.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/30">
                            <tr>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Route</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Schedule</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recent_deliveries as $delivery)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-gray-900">{{ $delivery->origin }}</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3 text-gray-300"></i>
                                        <span class="text-sm font-bold text-gray-900">{{ $delivery->destination }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        ];
                                        $class = $statusClasses[$delivery->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black border uppercase tracking-wider {{ $class }}">
                                        {{ str_replace('_', ' ', $delivery->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-gray-500 font-medium">
                                    {{ $delivery->departure_date->diffForHumans() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Activity Log (Right 1/3) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="activity" class="w-4 h-4 text-indigo-600"></i>
                        System Activity
                    </h3>
                    <a href="{{ route('activity-logs.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Audit</a>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($recent_logs as $log)
                            <li>
                                <div class="relative pb-8">
                                    @if (!$loop->last)
                                    <span class="absolute top-4 left-3 -ml-px h-full w-0.5 bg-gray-100" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center ring-4 ring-white">
                                                <i data-lucide="circle" class="w-2 h-2 text-indigo-500"></i>
                                            </span>
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1">
                                            <div>
                                                <p class="text-xs text-gray-500">
                                                    <span class="font-bold text-gray-900">{{ $log->user->name }}</span> 
                                                    {{ str_replace('_', ' ', $log->action) }}
                                                </p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-[10px] text-gray-400">
                                                {{ $log->created_at->diffForHumans(null, true) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status Distribution Chart
            new ApexCharts(document.querySelector("#statusChart"), {
                series: [{{ $chart_data['delivery_status']['pending'] }}, {{ $chart_data['delivery_status']['in_progress'] }}, {{ $chart_data['delivery_status']['completed'] }}],
                chart: { type: 'donut', height: 350 },
                labels: ['Pending', 'In Progress', 'Completed'],
                colors: ['#f59e0b', '#3b82f6', '#10b981'],
                legend: { position: 'bottom' },
                dataLabels: { enabled: false },
                plotOptions: { pie: { donut: { size: '75%' } } }
            }).render();

            // Utilization Chart
            new ApexCharts(document.querySelector("#utilizationChart"), {
                series: [{
                    name: 'Trucks',
                    data: [{{ $chart_data['truck_utilization']['available'] }}, {{ $chart_data['truck_utilization']['on_delivery'] }}, {{ $chart_data['truck_utilization']['maintenance'] }}]
                }],
                chart: { type: 'bar', height: 350, toolbar: { show: false } },
                xaxis: { categories: ['Available', 'On Delivery', 'Maintenance'] },
                colors: ['#6366f1'],
                plotOptions: { bar: { borderRadius: 10, columnWidth: '40%' } }
            }).render();
        });
    </script>
    @endpush
</x-app-layout>
