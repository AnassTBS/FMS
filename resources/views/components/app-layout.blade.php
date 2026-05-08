<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Fleet Management') }} - Operations Platform</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">
    @php
        $primaryLinks = [];

        if (auth()->check()) {
            $primaryLinks[] = ['label' => 'Dashboard', 'route' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'icon' => 'layout-dashboard'];

            if (auth()->user()->isAdmin() || auth()->user()->isDispatcher()) {
                $primaryLinks[] = ['label' => 'Deliveries', 'route' => route('deliveries.index'), 'active' => request()->routeIs('deliveries.*'), 'icon' => 'package'];
                $primaryLinks[] = ['label' => 'Drivers', 'route' => route('drivers.index'), 'active' => request()->routeIs('drivers.*'), 'icon' => 'users'];
                $primaryLinks[] = ['label' => 'Trucks', 'route' => route('trucks.index'), 'active' => request()->routeIs('trucks.*'), 'icon' => 'truck'];
            }

            $primaryLinks[] = ['label' => 'Fuel', 'route' => route('fuel-entries.index'), 'active' => request()->routeIs('fuel-entries.*'), 'icon' => 'fuel'];

            if (auth()->user()->isAdmin() || auth()->user()->isDispatcher()) {
                $primaryLinks[] = ['label' => 'Maintenance', 'route' => route('maintenances.index'), 'active' => request()->routeIs('maintenances.*'), 'icon' => 'wrench'];
                $primaryLinks[] = ['label' => 'Activity Logs', 'route' => route('activity-logs.index'), 'active' => request()->routeIs('activity-logs.*'), 'icon' => 'history'];
            }

            if (auth()->user()->isAdmin()) {
                $primaryLinks[] = ['label' => 'Users', 'route' => route('users.index'), 'active' => request()->routeIs('users.*'), 'icon' => 'shield-check'];
            }
        }
    @endphp

    <div class="min-h-screen lg:flex">
        @auth
        <aside class="hidden w-72 shrink-0 border-r border-slate-800 bg-slate-950 text-white lg:fixed lg:inset-y-0 lg:flex lg:flex-col">
            <div class="flex h-20 items-center px-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500 shadow-lg shadow-indigo-950/40">
                        <i data-lucide="truck" class="h-5 w-5 text-white"></i>
                    </span>
                    <span>
                        <span class="block text-lg font-extrabold tracking-tight">FMS Pro</span>
                        <span class="block text-xs font-semibold uppercase tracking-widest text-slate-400">Fleet operations</span>
                    </span>
                </a>
            </div>

            <nav class="flex-1 space-y-1 px-4 py-4">
                @foreach($primaryLinks as $link)
                    <x-nav-link :href="$link['route']" :active="$link['active']" :icon="$link['icon']">
                        {{ __($link['label']) }}
                    </x-nav-link>
                @endforeach
            </nav>

            <div class="border-t border-slate-800 p-4">
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500 text-sm font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">{{ Auth::user()->role }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg border border-slate-700 px-3 py-2 text-sm font-bold text-slate-300 transition hover:border-red-400/40 hover:bg-red-500/10 hover:text-red-200 focus:outline-none focus:ring-2 focus:ring-red-400/40">
                            <i data-lucide="log-out" class="h-4 w-4"></i>
                            Log out
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        @endauth

        <div class="flex min-h-screen flex-1 flex-col @auth lg:pl-72 @endauth">
            <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
                <div class="flex min-h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3 lg:hidden">
                        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="flex items-center gap-2">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600">
                                <i data-lucide="truck" class="h-5 w-5 text-white"></i>
                            </span>
                            <span class="text-lg font-extrabold tracking-tight text-slate-950">FMS Pro</span>
                        </a>
                    </div>

                    @auth
                    <nav class="hidden items-center gap-1 lg:flex">
                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold uppercase tracking-widest text-slate-500">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Operations live
                        </span>
                    </nav>
                    @endauth

                    <div class="flex items-center gap-3">
                        @auth
                            <div class="hidden text-right sm:block">
                                <p class="text-sm font-bold text-slate-950">{{ Auth::user()->name }}</p>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">{{ Auth::user()->role }}</p>
                            </div>
                            <div class="hidden h-9 w-px bg-slate-200 sm:block"></div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 transition hover:text-indigo-600">Log in</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm shadow-indigo-200 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Register</a>
                        @endauth
                    </div>
                </div>

                @auth
                <div class="border-t border-slate-100 px-4 py-2 sm:px-6 lg:hidden">
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        @foreach($primaryLinks as $link)
                            <a href="{{ $link['route'] }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg px-3 py-2 text-sm font-bold transition {{ $link['active'] ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}">
                                <i data-lucide="{{ $link['icon'] }}" class="h-4 w-4"></i>
                                {{ __($link['label']) }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endauth
            </header>

            @if (isset($header))
                <section class="border-b border-slate-200 bg-white">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </section>
            @endif

            <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    @if (session('success'))
                        <div class="mb-6 flex items-center rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm" role="alert">
                            <i data-lucide="check-circle" class="mr-3 h-5 w-5 shrink-0"></i>
                            <span class="text-sm font-semibold">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800 shadow-sm" role="alert">
                            <div class="mb-2 flex items-center">
                                <i data-lucide="alert-circle" class="mr-3 h-5 w-5 shrink-0"></i>
                                <span class="text-sm font-bold">Please correct the following errors:</span>
                            </div>
                            <ul class="list-inside list-disc text-sm font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
