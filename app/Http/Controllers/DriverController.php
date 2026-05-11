<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DriverController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin'),
        ];
    }
    /**
     * Display a listing of the drivers.
     */
    public function index(): View
    {
        $drivers = Driver::withCount('deliveries')->latest()->paginate(10);
        return view('drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new driver.
     */
    public function create(): View
    {
        $driverUsers = User::where('role', 'driver')
            ->whereDoesntHave('driver')
            ->orderBy('name')
            ->get();

        return view('drivers.create', compact('driverUsers'));
    }

    /**
     * Store a newly created driver in storage.
     */
    public function store(StoreDriverRequest $request): RedirectResponse
    {
        Driver::create($request->validated());

        return redirect()->route('drivers.index')
            ->with('success', 'Driver created successfully.');
    }

    /**
     * Display the specified driver.
     */
    public function show(Driver $driver): View
    {
        $driver->load('deliveries');
        return view('drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified driver.
     */
    public function edit(Driver $driver): View
    {
        $driverUsers = User::where('role', 'driver')
            ->where(function ($query) use ($driver) {
                $query->whereDoesntHave('driver')
                    ->orWhere('id', $driver->user_id);
            })
            ->orderBy('name')
            ->get();

        return view('drivers.edit', compact('driver', 'driverUsers'));
    }

    /**
     * Update the specified driver in storage.
     */
    public function update(UpdateDriverRequest $request, Driver $driver): RedirectResponse
    {
        $driver->update($request->validated());

        return redirect()->route('drivers.index')
            ->with('success', 'Driver updated successfully.');
    }

    /**
     * Remove the specified driver from storage.
     */
    public function destroy(Driver $driver): RedirectResponse
    {
        $hasActiveDeliveries = $driver->deliveries()
            ->whereIn('status', [\App\Models\Delivery::STATUS_ASSIGNED, \App\Models\Delivery::STATUS_IN_TRANSIT])
            ->exists();

        if ($hasActiveDeliveries) {
            return back()->with('error', 'Cannot delete driver with active deliveries in progress.');
        }

        $driver->delete();

        return redirect()->route('drivers.index')
            ->with('success', 'Driver deleted successfully.');
    }
}
