<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Driver workspace</p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                    {{ __('Driver Portal') }}
                </h2>
            </div>
            <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-bold text-slate-600">
                <i data-lucide="user" class="h-4 w-4 text-indigo-600"></i>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <section class="surface p-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500">Today at a glance</p>
                    <h3 class="mt-1 text-xl font-extrabold tracking-tight text-slate-950">Assignments and delivery status</h3>
                </div>
                <div class="inline-flex w-fit items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1.5 text-xs font-extrabold uppercase tracking-widest text-indigo-700">
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                    {{ Auth::user()->driver->status ?? 'Available' }}
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="metric-card">
                <div class="flex items-center gap-4">
                    <span class="rounded-xl bg-indigo-50 p-3 text-indigo-600">
                        <i data-lucide="package" class="h-6 w-6"></i>
                    </span>
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-widest text-slate-500">My deliveries</p>
                        <p class="mt-1 text-3xl font-extrabold tracking-tight text-slate-950">{{ $my_deliveries_count }}</p>
                    </div>
                </div>
            </div>

            <div class="metric-card border-emerald-100 bg-emerald-50">
                <div class="flex items-center gap-4">
                    <span class="rounded-xl bg-white p-3 text-emerald-600 shadow-sm">
                        <i data-lucide="check-circle" class="h-6 w-6"></i>
                    </span>
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-widest text-emerald-700">Completed</p>
                        <p class="mt-1 text-3xl font-extrabold tracking-tight text-emerald-950">{{ $completed_deliveries_count }}</p>
                    </div>
                </div>
            </div>

            <div class="metric-card border-blue-100 bg-blue-50">
                <div class="flex items-center gap-4">
                    <span class="rounded-xl bg-white p-3 text-blue-600 shadow-sm">
                        <i data-lucide="activity" class="h-6 w-6"></i>
                    </span>
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-widest text-blue-700">Active now</p>
                        <p class="mt-1 text-3xl font-extrabold tracking-tight text-blue-950">{{ $active_deliveries->count() }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="surface">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="flex items-center gap-2 text-sm font-extrabold text-slate-950">
                    <i data-lucide="map-pin" class="h-4 w-4 text-indigo-600"></i>
                    Current Assignments
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($active_deliveries as $delivery)
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div class="flex items-start gap-4">
                                <span class="rounded-xl border border-slate-200 bg-white p-3 text-indigo-600 shadow-sm">
                                    <i data-lucide="truck" class="h-5 w-5"></i>
                                </span>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-sm font-bold text-slate-950">{{ $delivery->origin }}</p>
                                        <i data-lucide="arrow-right" class="h-3.5 w-3.5 text-slate-400"></i>
                                        <p class="text-sm font-bold text-slate-950">{{ $delivery->destination }}</p>
                                    </div>
                                    <p class="mt-1 text-xs font-bold text-slate-500">Scheduled {{ $delivery->departure_date->format('M d, H:i') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('deliveries.show', $delivery) }}" class="btn-secondary">
                                <i data-lucide="map" class="h-4 w-4"></i>
                                View Route
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-white text-slate-400 shadow-sm">
                            <i data-lucide="circle-off" class="h-7 w-7"></i>
                        </div>
                        <p class="text-sm font-extrabold text-slate-700">No active deliveries assigned yet.</p>
                        <p class="mt-1 text-xs font-bold text-slate-500">New assignments will appear here when dispatch updates your route.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
