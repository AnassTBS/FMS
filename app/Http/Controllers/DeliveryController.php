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

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|dispatcher')->only(['create', 'store', 'destroy']);
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
        $trucks = Truck::all();
        $drivers = Driver::all();
        return view('deliveries.create', compact('trucks', 'drivers'));
    }

    /**
     * Store a newly created delivery in storage.
     */
    public function store(StoreDeliveryRequest $request): RedirectResponse
    {
        Delivery::create($request->validated());

        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery created successfully.');
    }

    /**
     * Display the specified delivery.
     */
    public function show(Delivery $delivery): View
    {
        if (Auth::user()->isDriver() && Auth::user()->driver->id !== $delivery->driver_id) {
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
        if (Auth::user()->isDriver() && Auth::user()->driver->id !== $delivery->driver_id) {
            abort(403);
        }

        $trucks = Truck::all();
        $drivers = Driver::all();
        return view('deliveries.edit', compact('delivery', 'trucks', 'drivers'));
    }

    /**
     * Update the specified delivery in storage.
     */
    public function update(UpdateDeliveryRequest $request, Delivery $delivery): RedirectResponse
    {
        if (Auth::user()->isDriver()) {
            if (Auth::user()->driver->id !== $delivery->driver_id) {
                abort(403);
            }
            // Drivers can only update status
            $delivery->update(['status' => $request->status]);
        } else {
            $delivery->update($request->validated());
        }

        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery updated successfully.');
    }

    /**
     * Remove the specified delivery from storage.
     */
    public function destroy(Delivery $delivery): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $delivery->delete();

        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery deleted successfully.');
    }
}
