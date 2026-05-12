<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Truck;
use App\Models\Driver;
use App\Http\Requests\StoreDeliveryRequest;
use App\Http\Requests\UpdateDeliveryRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DeliveryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin', only: ['create', 'store', 'destroy']),
        ];
    }

    /**
     * Display a listing of the deliveries.
     */
    public function index(): View
    {
        $query = Delivery::with(['truck', 'driver'])->latest();

        if (Auth::user()->isDriver()) {
            $driverProfile = Auth::user()->driver;
            $query->where('driver_id', $driverProfile ? $driverProfile->id : 0);
        }

        $deliveries = $query->paginate(10);
        
        // Auto-sync statuses for the current view to ensure data accuracy
        foreach ($deliveries as $delivery) {
            $delivery->syncStatus();
            $this->syncEntityStatuses($delivery);
        }

        return view('deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new delivery.
     */
    public function create(): View
    {
        // Only show available trucks and drivers
        $trucks = Truck::where('status', 'available')->get();
        $drivers = Driver::where('status', 'available')->get();
        return view('deliveries.create', compact('trucks', 'drivers'));
    }

    /**
     * Store a newly created delivery in storage.
     */
    public function store(StoreDeliveryRequest $request): RedirectResponse
    {
        $delivery = new Delivery($request->validated());
        
        // Planning Phase: Calculate expected fuel
        $delivery->calculateExpectedFuel();
        $delivery->status = Delivery::STATUS_ASSIGNED;
        $delivery->save();
        
        // Trigger automatic sync
        $delivery->syncStatus();
        $this->syncEntityStatuses($delivery);

        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery created and status synchronized successfully.');
    }

    /**
     * Display the specified delivery.
     */
    public function show(Delivery $delivery): View
    {
        if (Auth::user()->isDriver() && Auth::user()->driver?->id !== $delivery->driver_id) {
            abort(403);
        }

        $delivery->syncStatus();
        $this->syncEntityStatuses($delivery);
        $delivery->load(['truck', 'driver']);
        
        return view('deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified delivery.
     */
    public function edit(Delivery $delivery): View
    {
        if (Auth::user()->isDriver() && Auth::user()->driver?->id !== $delivery->driver_id) {
            abort(403);
        }

        if (Auth::user()->isDriver()) {
            return view('deliveries.edit', [
                'delivery' => $delivery,
                'trucks' => collect(),
                'drivers' => collect(),
            ]);
        }

        // Show available trucks/drivers + the currently assigned ones
        $trucks = Truck::where('status', 'available')
            ->orWhere('id', $delivery->truck_id)
            ->get();
        $drivers = Driver::where('status', 'available')
            ->orWhere('id', $delivery->driver_id)
            ->get();
            
        return view('deliveries.edit', compact('delivery', 'trucks', 'drivers'));
    }

    /**
     * Update the specified delivery in storage.
     */
    public function update(UpdateDeliveryRequest $request, Delivery $delivery): RedirectResponse
    {
        if (Auth::user()->isDriver()) {
            if ($delivery->driver_id !== Auth::user()->driver?->id) {
                abort(403);
            }

            if ($delivery->actual_fuel !== null) {
                return back()->withErrors([
                    'actual_fuel' => 'Fuel data has already been submitted and cannot be edited.',
                ]);
            }

            $validated = $request->validated();

            $delivery->status = $validated['status'];

            if ($delivery->status === Delivery::STATUS_DELIVERED) {
                $delivery->arrival_date ??= now();
                $delivery->actual_fuel = $validated['actual_fuel'] ?? null;
                $delivery->fuel_cost = $validated['fuel_cost'] ?? null;

                if ($delivery->actual_fuel !== null) {
                    $delivery->calculateFuelEfficiency();
                }
            }

            $delivery->save();
            $this->syncEntityStatuses($delivery);

            return redirect()->route('deliveries.index')
                ->with('success', 'Delivery updated and fuel monitoring data recorded.');
        }

        $oldTruckId = $delivery->truck_id;
        $oldDriverId = $delivery->driver_id;

        $delivery->update($request->validated());
        $delivery->calculateExpectedFuel();
        $delivery->save();

        // Execution/Monitoring Phase: Handle fuel efficiency on completion
        // Admin has read access only for delivery fuel monitoring values.

        // Reset old assets if they were swapped
        if ($oldTruckId !== $delivery->truck_id) {
            Truck::find($oldTruckId)->update(['status' => 'available']);
        }
        if ($oldDriverId !== $delivery->driver_id) {
            Driver::find($oldDriverId)->update(['status' => 'available']);
        }

        $this->syncEntityStatuses($delivery);

        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery updated and assets synchronized.');
    }

    /**
     * Remove the specified delivery from storage.
     */
    public function destroy(Delivery $delivery): RedirectResponse
    {
        // Reset truck and driver to available before deleting
        $delivery->truck?->update(['status' => 'available']);
        $delivery->driver?->update(['status' => 'available']);

        $delivery->delete();

        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery deleted successfully.');
    }

    /**
     * Sync truck and driver statuses based on delivery status.
     * Mappings: Assigned/In Transit -> Truck:on_delivery / Driver:busy, Delivered -> available.
     */
    private function syncEntityStatuses(Delivery $delivery): void
    {
        $truck = $delivery->truck;
        $driver = $delivery->driver;

        if (!$truck || !$driver) return;

        if (in_array($delivery->status, [Delivery::STATUS_ASSIGNED, Delivery::STATUS_IN_TRANSIT], true)) {
            $truck->update(['status' => 'on_delivery']);
            $driver->update(['status' => 'busy']);
        } elseif ($delivery->status === Delivery::STATUS_DELIVERED) {
            $truck->update(['status' => 'available']);
            $driver->update(['status' => 'available']);
        }
    }
}
