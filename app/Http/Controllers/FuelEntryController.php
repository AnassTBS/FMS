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
        return view('fuel_entries.create', compact('trucks'));
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
