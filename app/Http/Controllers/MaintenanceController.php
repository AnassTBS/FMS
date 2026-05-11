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
            new Middleware('role:admin'),
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

        $truck = Truck::find($validated['truck_id']);

        // Logic fix: Prevent maintenance if truck is on delivery or reserved
        if (in_array($truck->status, ['on_delivery', 'busy', 'reserved'])) {
            return back()->withInput()->withErrors([
                'truck_id' => "Cannot schedule maintenance while the truck is assigned or on delivery."
            ]);
        }

        // Logic fix: Check if truck already has a pending maintenance
        $pendingMaintenance = Maintenance::where('truck_id', $validated['truck_id'])
            ->where('is_completed', false)
            ->exists();

        if ($pendingMaintenance) {
            return back()->withInput()->withErrors([
                'truck_id' => "This truck already has a pending maintenance record."
            ]);
        }

        Maintenance::create($validated);

        // Set the truck to maintenance status
        $truck->update(['status' => 'maintenance']);

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance scheduled successfully and truck status updated.');
    }

    public function update(Request $request, Maintenance $maintenance): RedirectResponse
    {
        $maintenance->update([
            'is_completed' => $request->has('is_completed')
        ]);

        $maintenance->refresh();

        if ($maintenance->is_completed) {
            // When maintenance is done, ensure the truck is available if it was in maintenance status
            if ($maintenance->truck?->status === 'maintenance') {
                $maintenance->truck->update(['status' => 'available']);
            }
        }

        return back()->with('success', 'Maintenance status updated.');
    }

    public function destroy(Maintenance $maintenance): RedirectResponse
    {
        // If the maintenance was not completed, the truck is still blocked.
        // Restore it to available before removing the record.
        if (! $maintenance->is_completed) {
            $maintenance->truck?->update(['status' => 'available']);
        }

        $maintenance->delete();

        return redirect()->route('maintenances.index')
            ->with('success', 'Maintenance record removed.');
    }
}
