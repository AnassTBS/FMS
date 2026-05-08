<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Fleet Management') }} - Delivery Module</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                                <div class="bg-indigo-600 p-1.5 rounded-lg group-hover:bg-indigo-700 transition-colors">
                                    <i data-lucide="truck" class="w-6 h-6 text-white"></i>
                                </div>
                                <span class="text-xl font-bold tracking-tight text-gray-900">FMS<span class="text-indigo-600">Pro</span></span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            @auth
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="layout-dashboard">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                                
                                @if(auth()->user()->isAdmin() || auth()->user()->isDispatcher())
                                <x-nav-link :href="route('deliveries.index')" :active="request()->routeIs('deliveries.*')" icon="package">
                                    {{ __('Deliveries') }}
                                </x-nav-link>

                                <x-nav-link :href="route('drivers.index')" :active="request()->routeIs('drivers.*')" icon="users">
                                    {{ __('Drivers') }}
                                </x-nav-link>

                                <x-nav-link :href="route('trucks.index')" :active="request()->routeIs('trucks.*')" icon="container">
                                    {{ __('Trucks') }}
                                </x-nav-link>
                                @endif

                                <x-nav-link :href="route('fuel-entries.index')" :active="request()->routeIs('fuel-entries.*')" icon="fuel">
                                    {{ __('Fuel') }}
                                </x-nav-link>

                                @if(auth()->user()->isAdmin() || auth()->user()->isDispatcher())
                                <x-nav-link :href="route('maintenances.index')" :active="request()->routeIs('maintenances.*')" icon="wrench">
                                    {{ __('Maintenance') }}
                                </x-nav-link>

                                <x-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')" icon="history">
                                    {{ __('Activity Logs') }}
                                </x-nav-link>
                                @endif

                                @if(auth()->user()->isAdmin())
                                <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" icon="shield-check">
                                    {{ __('Users') }}
                                </x-nav-link>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-6">
                        @auth
                            <div class="flex flex-col items-end">
                                <span class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</span>
                                <span class="text-[11px] font-medium uppercase tracking-wider text-gray-500">{{ Auth::user()->role }}</span>
                            </div>
                            
                            <div class="h-8 w-px bg-gray-200"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-red-600 transition-colors duration-200">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                    <span>Log Out</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-indigo-600 transition-colors">Log in</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Main Content -->
        <main class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Notifications -->
                @if (session('success'))
                    <div class="mb-6 flex items-center p-4 text-green-800 bg-green-50 border-l-4 border-green-500 rounded-r-lg" role="alert">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 text-red-800 bg-red-50 border-l-4 border-red-500 rounded-r-lg" role="alert">
                        <div class="flex items-center mb-2">
                            <i data-lucide="alert-circle" class="w-5 h-5 mr-3"></i>
                            <span class="text-sm font-bold">Please correct the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm">
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

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
