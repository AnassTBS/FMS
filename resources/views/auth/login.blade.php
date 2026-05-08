<x-app-layout>
    <div class="flex min-h-[70vh] flex-col items-center justify-center">
        <div class="surface w-full max-w-md p-8">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-200">
                    <i data-lucide="truck" class="h-6 w-6"></i>
                </div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-950">Login to FMS</h2>
                <p class="mt-2 text-sm font-medium text-slate-500">Access your fleet operations workspace.</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700">Email</label>
                    <input id="email" class="mt-1 block w-full" type="email" name="email" value="{{ old('email') }}" required autofocus />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                    <input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                        <span class="ml-2 text-sm font-medium text-slate-600">Remember me</span>
                    </label>
                </div>

                <div class="mt-6 flex items-center justify-between gap-4">
                    <a class="text-sm font-bold text-slate-600 transition hover:text-indigo-600" href="{{ route('register') }}">
                        Need an account?
                    </a>

                    <button type="submit" class="btn-primary">
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
