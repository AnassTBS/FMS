<?php

namespace App\Http\Controllers;

use App\Models\FuelEntry;
use App\Models\Delivery;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

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
        $query = FuelEntry::with(['truck', 'user'])->latest();

        if (Auth::user()->isDriver()) {
            // Drivers can see all logs or maybe just their own? 
            // The prompt says "Fuel tracking and monitoring module", usually implies visibility for management.
            // But let's keep the query as is for now or filter if needed.
        }

        $fuelEntries = $query->paginate(15);
        $deliveryFuelLogs = Delivery::with(['truck', 'driver'])
            ->whereNotNull('expected_fuel')
            ->latest()
            ->paginate(15, ['*'], 'deliveries_page');

        // Calculate dashboard stats
        $stats = [
            'total_fuel' => FuelEntry::sum('liters'),
            'total_cost' => FuelEntry::sum('amount'),
            'avg_price' => FuelEntry::count() > 0 ? FuelEntry::sum('amount') / FuelEntry::sum('liters') : 0,
            'avg_consumption' => FuelEntry::whereNotNull('real_consumption')->avg('real_consumption') ?? 0,
        ];

        return view('fuel_entries.index', compact('fuelEntries', 'deliveryFuelLogs', 'stats'));
    }

    /**
     * Show the form for creating a new fuel entry.
     */
    public function create(): View
    {
        $trucks = Truck::orderBy('registration_number')->get();
        
        $defaultTruckId = null;
        if (Auth::user()->isDriver() && Auth::user()->driver) {
            $defaultTruckId = Auth::user()->driver->deliveries()
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
            'fuel_station' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $truck = Truck::findOrFail($validated['truck_id']);

        // 1. Safety Rule: Prevent mileage rollback
        $lastEntry = FuelEntry::where('truck_id', $validated['truck_id'])
            ->orderBy('mileage', 'desc')
            ->first();

        if ($lastEntry && $validated['mileage'] < $lastEntry->mileage) {
            return back()->withInput()->withErrors([
                'mileage' => "Mileage cannot be less than the previous entry for this truck (" . number_format($lastEntry->mileage) . " km)."
            ]);
        }

        // 2. Automatic Calculations
        $distanceTraveled = null;
        $realConsumption = null;
        $status = 'normal';

        if ($lastEntry) {
            $distanceTraveled = $validated['mileage'] - $lastEntry->mileage;
            
            if ($distanceTraveled > 0) {
                $realConsumption = ($validated['liters'] / $distanceTraveled) * 100;
                
                // 3. Abnormal Consumption Detection
                $difference = $realConsumption - $truck->average_consumption;
                
                if ($difference > 15) {
                    $status = 'critical';
                } elseif ($difference > 5) {
                    $status = 'warning';
                } else {
                    $status = 'normal';
                }
            }
        }

        // 4. Create Entry
        FuelEntry::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'distance_traveled' => $distanceTraveled,
            'real_consumption' => $realConsumption,
            'status' => $status,
        ]));

        return redirect()->route('fuel-entries.index')
            ->with('success', 'Fuel entry recorded and consumption analyzed.');
    }

    /**
     * Remove the specified fuel entry from storage.
     */
    public function destroy(FuelEntry $fuelEntry): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $fuelEntry->delete();

        return redirect()->route('fuel-entries.index')
            ->with('success', 'Fuel entry deleted successfully.');
    }
}
