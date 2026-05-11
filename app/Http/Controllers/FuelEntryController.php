<?php

namespace App\Http\Controllers;

use App\Models\FuelEntry;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FuelEntryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin|driver'),
        ];
    }

    /**
     * Display a listing of the fuel entries.
     */
    public function index(): View
    {
        $query = FuelEntry::with('truck')->latest();

        // If driver, maybe they only see their truck's fuel? 
        // For now, let's keep it simple or filter by truck if needed.

        $fuelEntries = $query->paginate(15);
        return view('fuel_entries.index', compact('fuelEntries'));
    }

    /**
     * Show the form for creating a new fuel entry.
     */
    public function create(): View
    {
        $trucks = Truck::orderBy('registration_number')->get();
        
        // If the user is a driver, try to find their current truck
        $defaultTruckId = null;
        if (auth()->user()->isDriver() && auth()->user()->driver) {
            $defaultTruckId = auth()->user()->driver->deliveries()
                ->whereIn('status', [\App\Models\Delivery::STATUS_ASSIGNED, \App\Models\Delivery::STATUS_IN_TRANSIT])
                ->latest()
                ->first()?->truck_id;
        }

        return view('fuel_entries.create', compact('trucks', 'defaultTruckId'));
    }

    /**
     * Store a newly created fuel entry in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'truck_id' => 'required|exists:trucks,id',
            'date' => 'required|date',
            'liters' => 'required|numeric|min:0.1',
            'amount' => 'required|numeric|min:0.1',
            'mileage' => 'required|numeric|min:0',
        ]);

        // Logic fix: Ensure mileage is not decreasing
        $lastEntry = FuelEntry::where('truck_id', $validated['truck_id'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastEntry && $validated['mileage'] < $lastEntry->mileage) {
            return back()->withInput()->withErrors([
                'mileage' => "Mileage cannot be less than the previous entry for this truck (" . number_format($lastEntry->mileage) . " km)."
            ]);
        }

        FuelEntry::create($validated);

        return redirect()->route('fuel-entries.index')
            ->with('success', 'Fuel entry recorded successfully.');
    }

    /**
     * Remove the specified fuel entry from storage.
     */
    public function destroy(FuelEntry $fuelEntry): RedirectResponse
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $fuelEntry->delete();

        return redirect()->route('fuel-entries.index')
            ->with('success', 'Fuel entry deleted successfully.');
    }
}
