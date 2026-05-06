<x-app-layout>
    <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-8">Create Account</h2>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                    <input id="name" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" type="text" name="name" value="{{ old('name') }}" required autofocus />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input id="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" type="email" name="email" value="{{ old('email') }}" required />
                </div>

                <!-- Role Selection -->
                <div class="mt-4">
                    <label for="role" class="block font-medium text-sm text-gray-700">Role</label>
                    <select id="role" name="role" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" required>
                        <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Driver</option>
                        <option value="dispatcher" {{ old('role') == 'dispatcher' ? 'selected' : '' }}>Dispatcher</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    <input id="password" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" type="password" name="password" required autocomplete="new-password" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" type="password" name="password_confirmation" required />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        Already registered?
                    </a>

                    <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
