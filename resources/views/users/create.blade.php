<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-widest text-indigo-600">Access control</p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">
                {{ __('Add New User') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="surface">
            <div class="p-6">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>

                                <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Driver</option>
                            </select>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3 border-t border-slate-100 pt-6">
                        <a href="{{ route('users.index') }}" class="btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
