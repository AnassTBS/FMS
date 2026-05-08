<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Dispatch workspace</p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                    {{ __('Dispatch Control Center') }}
                </h2>
            </div>
            <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-bold text-slate-600">
                <i data-lucide="calendar" class="h-4 w-4 text-indigo-600"></i>
                <span>{{ now()->format('F d, Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="metric-card">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-widest text-slate-500">Total deliveries</p>
                        <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950">{{ $stats['total_deliveries'] }}</p>
                    </div>
                    <span class="rounded-xl bg-indigo-50 p-2.5 text-indigo-600">
                        <i data-lucide="package" class="h-5 w-5"></i>
                    </span>
                </div>
                <p class="mt-5 text-xs font-bold text-slate-500">Across all dispatch records</p>
            </div>

            <div class="metric-card border-blue-100 bg-blue-50">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-widest text-blue-700">In progress</p>
                        <p class="mt-3 text-3xl font-extrabold tracking-tight text-blue-950">{{ $stats['active_deliveries'] }}</p>
                    </div>
                    <span class="rounded-xl bg-white p-2.5 text-blue-600 shadow-sm">
                        <i data-lucide="truck" class="h-5 w-5"></i>
                    </span>
                </div>
                <p class="mt-5 flex items-center gap-2 text-xs font-bold text-blue-700">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    Live operations
                </p>
            </div>

            <div class="metric-card border-emerald-100 bg-emerald-50">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-widest text-emerald-700">Available trucks</p>
                        <p class="mt-3 text-3xl font-extrabold tracking-tight text-emerald-950">{{ $stats['available_trucks'] }}</p>
                    </div>
                    <span class="rounded-xl bg-white p-2.5 text-emerald-600 shadow-sm">
                        <i data-lucide="container" class="h-5 w-5"></i>
                    </span>
                </div>
                <p class="mt-5 text-xs font-bold text-emerald-700">{{ $stats['trucks_on_delivery'] }} currently deployed</p>
            </div>

            <div class="metric-card border-amber-100 bg-amber-50">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-widest text-amber-700">Available drivers</p>
                        <p class="mt-3 text-3xl font-extrabold tracking-tight text-amber-950">{{ $stats['available_drivers'] }}</p>
                    </div>
                    <span class="rounded-xl bg-white p-2.5 text-amber-600 shadow-sm">
                        <i data-lucide="users" class="h-5 w-5"></i>
                    </span>
                </div>
                <p class="mt-5 text-xs font-bold text-amber-700">{{ $stats['busy_drivers'] }} currently busy</p>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="surface p-6">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="flex items-center gap-2 text-sm font-extrabold text-slate-950">
                        <i data-lucide="pie-chart" class="h-4 w-4 text-indigo-600"></i>
                        Delivery Status
                    </h3>
                    <span class="text-xs font-bold text-slate-500">{{ $stats['total_deliveries'] }} total</span>
                </div>
                <div id="statusChart" class="min-h-[300px]"></div>
            </div>

            <div class="surface p-6">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="flex items-center gap-2 text-sm font-extrabold text-slate-950">
                        <i data-lucide="bar-chart-3" class="h-4 w-4 text-indigo-600"></i>
                        Fleet Utilization
                    </h3>
                    <span class="text-xs font-bold text-slate-500">{{ $stats['available_trucks'] + $stats['trucks_on_delivery'] }} tracked</span>
                </div>
                <div id="utilizationChart" class="min-h-[300px]"></div>
            </div>
        </section>

        <section class="surface">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="flex items-center gap-2 text-sm font-extrabold text-slate-950">
                    <i data-lucide="route" class="h-4 w-4 text-indigo-600"></i>
                    Recent Deliveries
                </h3>
                <a href="{{ route('deliveries.index') }}" class="inline-flex items-center gap-1 text-xs font-extrabold text-indigo-600 hover:text-indigo-700">
                    View all
                    <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left">Route</th>
                            <th class="px-6 py-3 text-left">Driver</th>
                            <th class="px-6 py-3 text-left">Truck</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Schedule</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recent_deliveries as $delivery)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex min-w-64 items-center gap-2">
                                    <span class="text-sm font-bold text-slate-950">{{ $delivery->origin }}</span>
                                    <i data-lucide="arrow-right" class="h-3.5 w-3.5 text-slate-400"></i>
                                    <span class="text-sm font-bold text-slate-950">{{ $delivery->destination }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-600">{{ $delivery->driver->full_name }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-600">{{ $delivery->truck->registration_number }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    ];
                                    $class = $statusClasses[$delivery->status] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                                @endphp
                                <span class="status-badge {{ $class }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ str_replace('_', ' ', $delivery->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-xs font-bold text-slate-500">
                                {{ $delivery->departure_date->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm font-bold text-slate-500">No deliveries yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new ApexCharts(document.querySelector("#statusChart"), {
                series: [{{ $chart_data['delivery_status']['pending'] }}, {{ $chart_data['delivery_status']['in_progress'] }}, {{ $chart_data['delivery_status']['completed'] }}],
                chart: { type: 'donut', height: 320 },
                labels: ['Pending', 'In Progress', 'Completed'],
                colors: ['#f59e0b', '#2563eb', '#10b981'],
                legend: { position: 'bottom', fontWeight: 700 },
                dataLabels: { enabled: false },
                stroke: { width: 0 },
                plotOptions: { pie: { donut: { size: '72%', labels: { show: true, total: { show: true, label: 'Deliveries' } } } } }
            }).render();

            new ApexCharts(document.querySelector("#utilizationChart"), {
                series: [{
                    name: 'Trucks',
                    data: [{{ $chart_data['truck_utilization']['available'] }}, {{ $chart_data['truck_utilization']['on_delivery'] }}, {{ $chart_data['truck_utilization']['maintenance'] }}]
                }],
                chart: { type: 'bar', height: 320, toolbar: { show: false } },
                xaxis: { categories: ['Available', 'On Delivery', 'Maintenance'] },
                colors: ['#4f46e5'],
                grid: { borderColor: '#eef2f7' },
                plotOptions: { bar: { borderRadius: 8, columnWidth: '42%' } }
            }).render();
        });
    </script>
    @endpush
</x-app-layout>
