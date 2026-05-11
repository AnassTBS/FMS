<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Http\Requests\StoreTruckRequest;
use App\Http\Requests\UpdateTruckRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TruckController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin'),
        ];
    }
    /**
     * Display a listing of the trucks.
     */
    public function index(): View
    {
        $trucks = Truck::withCount('deliveries')->latest()->paginate(10);
        return view('trucks.index', compact('trucks'));
    }

    /**
     * Show the form for creating a new truck.
     */
    public function create(): View
    {
        return view('trucks.create');
    }

    /**
     * Store a newly created truck in storage.
     */
    public function store(StoreTruckRequest $request): RedirectResponse
    {
        Truck::create($request->validated());

        return redirect()->route('trucks.index')
            ->with('success', 'Truck created successfully.');
    }

    /**
     * Display the specified truck.
     */
    public function show(Truck $truck): View
    {
        $truck->load(['deliveries' => function($q) {
            $q->latest()->take(5);
        }, 'maintenances' => function($q) {
            $q->latest()->take(5);
        }, 'fuelEntries' => function($q) {
            $q->latest()->take(5);
        }]);
        
        return view('trucks.show', compact('truck'));
    }

    /**
     * Show the form for editing the specified truck.
     */
    public function edit(Truck $truck): View
    {
        return view('trucks.edit', compact('truck'));
    }

    /**
     * Update the specified truck in storage.
     */
    public function update(UpdateTruckRequest $request, Truck $truck): RedirectResponse
    {
        $truck->update($request->validated());

        return redirect()->route('trucks.index')
            ->with('success', 'Truck updated successfully.');
    }

    /**
     * Remove the specified truck from storage.
     */
    public function destroy(Truck $truck): RedirectResponse
    {
        $hasActiveDeliveries = $truck->deliveries()
            ->whereIn('status', [\App\Models\Delivery::STATUS_ASSIGNED, \App\Models\Delivery::STATUS_IN_TRANSIT])
            ->exists();

        if ($hasActiveDeliveries) {
            return back()->with('error', 'Cannot delete truck while it is assigned to an active delivery.');
        }

        $truck->delete();

        return redirect()->route('trucks.index')
            ->with('success', 'Truck deleted successfully.');
    }
}
