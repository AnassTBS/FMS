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
            new Middleware('role:admin|dispatcher', only: ['create', 'store', 'destroy']),
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
        $delivery = Delivery::create($request->validated());
        
        $this->syncEntityStatuses($delivery);

        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery created successfully.');
    }

    /**
     * Display the specified delivery.
     */
    public function show(Delivery $delivery): View
    {
        if (Auth::user()->isDriver() && Auth::user()->driver?->id !== $delivery->driver_id) {
            abort(403);
        }

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
        $oldTruckId = $delivery->truck_id;
        $oldDriverId = $delivery->driver_id;

        if (Auth::user()->isDriver()) {
            if (Auth::user()->driver?->id !== $delivery->driver_id) {
                abort(403);
            }
            $delivery->update([
                'status' => $request->status,
                'arrival_date' => $request->status === Delivery::STATUS_DELIVERED ? now() : $delivery->arrival_date,
            ]);
        } else {
            $delivery->update($request->validated());
        }

        // If truck or driver changed, reset the old ones to available
        if ($oldTruckId !== $delivery->truck_id) {
            Truck::find($oldTruckId)->update(['status' => 'available']);
        }
        if ($oldDriverId !== $delivery->driver_id) {
            Driver::find($oldDriverId)->update(['status' => 'available']);
        }

        $this->syncEntityStatuses($delivery);

        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery updated successfully.');
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
     */
    private function syncEntityStatuses(Delivery $delivery): void
    {
        $truck = $delivery->truck;
        $driver = $delivery->driver;

        if ($delivery->status === Delivery::STATUS_IN_TRANSIT) {
            $truck->update(['status' => 'on_delivery']);
            $driver->update(['status' => 'busy']);
        } elseif ($delivery->status === Delivery::STATUS_DELIVERED) {
            $truck->update(['status' => 'available']);
            $driver->update(['status' => 'available']);
        } else {
            // For assigned deliveries, keep the truck and driver visible as available until transit starts.
            // But validation prevents multiple active assignments, so we can safely reset.
            $truck->update(['status' => 'available']);
            $driver->update(['status' => 'available']);
        }
    }
}
