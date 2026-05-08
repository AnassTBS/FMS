<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MaintenanceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin|dispatcher'),
        ];
    }

    public function index(): View
    {
        $maintenances = Maintenance::with('truck')->latest()->paginate(15);
        return view('maintenances.index', compact('maintenances'));
    }

    public function create(): View
    {
        $trucks = Truck::orderBy('registration_number')->get();
        return view('maintenances.create', compact('trucks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'truck_id' => 'required|exists:trucks,id',
            'description' => 'required|string|max:255',
            'target_mileage' => 'required|numeric|min:0',
        ]);

        Maintenance::create($validated);

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance scheduled successfully.');
    }

    public function update(Request $request, Maintenance $maintenance): RedirectResponse
    {
        $maintenance->update([
            'is_completed' => $request->has('is_completed')
        ]);

        if ($maintenance->is_completed) {
            // When maintenance is done, ensure the truck is available if it was in maintenance status
            if ($maintenance->truck->status === 'maintenance') {
                $maintenance->truck->update(['status' => 'available']);
            }
        }

        return back()->with('success', 'Maintenance status updated.');
    }

    public function destroy(Maintenance $maintenance): RedirectResponse
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance record removed.');
    }
}
