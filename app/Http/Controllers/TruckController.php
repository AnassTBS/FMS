<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Http\Requests\StoreTruckRequest;
use App\Http\Requests\UpdateTruckRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TruckController extends Controller
{
    /**
     * Display a listing of the trucks.
     */
    public function index(): View
    {
        $trucks = Truck::latest()->paginate(10);
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
        $truck->load('deliveries');
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
        if ($truck->deliveries()->exists()) {
            return back()->with('error', 'Cannot delete truck with associated deliveries.');
        }

        $truck->delete();

        return redirect()->route('trucks.index')
            ->with('success', 'Truck deleted successfully.');
    }
}
