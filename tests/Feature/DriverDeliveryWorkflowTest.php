<?php

namespace Tests\Feature;

use App\Models\Delivery;
use App\Models\Driver;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverDeliveryWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigned_delivery_appears_on_linked_driver_dashboard(): void
    {
        [$driverUser, $driver, $delivery] = $this->createAssignedDelivery();
        [, , $otherDelivery] = $this->createAssignedDelivery();

        $this->actingAs($driverUser)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee($delivery->origin)
            ->assertSee($delivery->destination)
            ->assertSee('Assigned')
            ->assertDontSee($otherDelivery->origin)
            ->assertDontSee($otherDelivery->destination);
    }

    public function test_driver_can_only_view_their_own_deliveries(): void
    {
        [$driverUser, , $delivery] = $this->createAssignedDelivery();
        [, , $otherDelivery] = $this->createAssignedDelivery();

        $this->actingAs($driverUser)->get(route('deliveries.index'))->assertSee($delivery->origin);
        $this->actingAs($driverUser)->get(route('deliveries.show', $delivery))->assertOk();
        $this->actingAs($driverUser)->get(route('deliveries.show', $otherDelivery))->assertForbidden();
    }

    public function test_driver_can_update_only_their_own_delivery_status(): void
    {
        [$driverUser, , $delivery] = $this->createAssignedDelivery();
        [, , $otherDelivery] = $this->createAssignedDelivery();

        $this->actingAs($driverUser)
            ->put(route('deliveries.update', $delivery), [
                'status' => Delivery::STATUS_IN_TRANSIT,
            ])
            ->assertRedirect(route('deliveries.index'));

        $this->assertSame(Delivery::STATUS_IN_TRANSIT, $delivery->fresh()->status);

        $this->actingAs($driverUser)
            ->put(route('deliveries.update', $otherDelivery), [
                'status' => Delivery::STATUS_DELIVERED,
                'actual_fuel' => 50,
                'fuel_cost' => 700,
            ])
            ->assertForbidden();

        $this->assertSame(Delivery::STATUS_ASSIGNED, $otherDelivery->fresh()->status);
    }

    public function test_driver_delivery_completion_sets_arrival_timestamp(): void
    {
        [$driverUser, , $delivery] = $this->createAssignedDelivery();

        $this->actingAs($driverUser)
            ->put(route('deliveries.update', $delivery), [
                'status' => Delivery::STATUS_DELIVERED,
                'actual_fuel' => 50,
                'fuel_cost' => 700,
            ])
            ->assertRedirect(route('deliveries.index'));

        $delivery->refresh();

        $this->assertSame(Delivery::STATUS_DELIVERED, $delivery->status);
        $this->assertNotNull($delivery->arrival_date);
    }

    public function test_driver_cannot_access_fleet_management_resources(): void
    {
        [$driverUser, $driver] = $this->createAssignedDelivery();
        $truck = Truck::first();

        $this->actingAs($driverUser)->get(route('trucks.index'))->assertForbidden();
        $this->actingAs($driverUser)->get(route('trucks.show', $truck))->assertForbidden();
        $this->actingAs($driverUser)->get(route('drivers.index'))->assertForbidden();
        $this->actingAs($driverUser)->get(route('drivers.show', $driver))->assertForbidden();
    }

    private function createAssignedDelivery(): array
    {
        $driverUser = User::factory()->create(['role' => 'driver']);
        $driver = Driver::create([
            'name' => $driverUser->name,
            'user_id' => $driverUser->id,
            'license_number' => fake()->unique()->bothify('LIC-####'),
            'phone' => fake()->phoneNumber(),
            'status' => 'available',
        ]);
        $truck = Truck::create([
            'registration_number' => fake()->unique()->bothify('TRK-####'),
            'model' => 'Volvo FH',
            'capacity' => 12000,
            'average_consumption' => 35,
            'status' => 'available',
        ]);
        $delivery = Delivery::create([
            'truck_id' => $truck->id,
            'driver_id' => $driver->id,
            'origin' => fake()->unique()->city(),
            'destination' => fake()->unique()->city(),
            'distance_km' => 120,
            'expected_fuel' => 42,
            'status' => Delivery::STATUS_ASSIGNED,
            'departure_date' => now()->addHour(),
        ]);

        return [$driverUser, $driver, $delivery];
    }
}
