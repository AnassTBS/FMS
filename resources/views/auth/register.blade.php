<x-app-layout>
    <div class="flex min-h-[70vh] flex-col items-center justify-center">
        <div class="surface w-full max-w-md p-8">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-200">
                    <i data-lucide="user-plus" class="h-6 w-6"></i>
                </div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-950">Create Account</h2>
                <p class="mt-2 text-sm font-medium text-slate-500">Set up access for the fleet operations platform.</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700">Name</label>
                    <input id="name" class="mt-1 block w-full" type="text" name="name" value="{{ old('name') }}" required autofocus />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <label for="email" class="block text-sm font-bold text-slate-700">Email</label>
                    <input id="email" class="mt-1 block w-full" type="email" name="email" value="{{ old('email') }}" required />
                </div>

                <!-- Role Selection -->
                <div class="mt-4">
                    <label for="role" class="block text-sm font-bold text-slate-700">Role</label>
                    <select id="role" name="role" class="mt-1 block w-full" required>
                        <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Driver</option>

                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                    <input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700">Confirm Password</label>
                    <input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required />
                </div>

                <div class="mt-6 flex items-center justify-between gap-4">
                    <a class="text-sm font-bold text-slate-600 transition hover:text-indigo-600" href="{{ route('login') }}">
                        Already registered?
                    </a>

                    <button type="submit" class="btn-primary">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
