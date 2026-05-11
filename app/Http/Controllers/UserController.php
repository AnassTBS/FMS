<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin'),
        ];
    }

    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'in:admin,dispatcher,driver'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,dispatcher,driver'],
        ]);

        // Logic fix: Prevent admin from demoting themselves to a non-admin role
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return back()->with('error', 'You cannot demote yourself from the admin role.');
        }

        $user->update($request->only('name', 'email', 'role'));

        if ($request->filled('password')) {
            $request->validate([
                'password' => [Password::defaults()],
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        \App\Models\ActivityLog::log('user_updated', "Updated profile for {$user->name}", $user);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        // Logic fix: Prevent deleting user linked to a driver with active work
        if ($user->driver && $user->driver->deliveries()->whereIn('status', [\App\Models\Delivery::STATUS_ASSIGNED, \App\Models\Delivery::STATUS_IN_TRANSIT])->exists()) {
            return back()->with('error', 'Cannot delete user linked to a driver with active deliveries.');
        }

        \App\Models\ActivityLog::log('user_deleted', "Deleted user account {$user->name} ({$user->email})");

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
