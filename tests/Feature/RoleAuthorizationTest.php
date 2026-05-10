<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dispatcher_cannot_access_admin_only_resources(): void
    {
        $dispatcher = User::factory()->create(['role' => 'dispatcher']);

        $this->actingAs($dispatcher)->get(route('users.index'))->assertForbidden();
        $this->actingAs($dispatcher)->get(route('activity-logs.index'))->assertForbidden();
        $this->actingAs($dispatcher)->get(route('maintenances.index'))->assertForbidden();
        $this->actingAs($dispatcher)->get(route('fuel-entries.index'))->assertForbidden();
    }

    public function test_dispatcher_can_access_operational_resources(): void
    {
        $dispatcher = User::factory()->create(['role' => 'dispatcher']);

        $this->actingAs($dispatcher)->get(route('deliveries.index'))->assertOk();
        $this->actingAs($dispatcher)->get(route('deliveries.create'))->assertOk();
        $this->actingAs($dispatcher)->get(route('trucks.index'))->assertOk();
        $this->actingAs($dispatcher)->get(route('trucks.create'))->assertOk();
        $this->actingAs($dispatcher)->get(route('drivers.index'))->assertOk();
        $this->actingAs($dispatcher)->get(route('drivers.create'))->assertOk();
    }

    public function test_dispatcher_navigation_excludes_admin_only_links(): void
    {
        $dispatcher = User::factory()->create(['role' => 'dispatcher']);

        $this->actingAs($dispatcher)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Deliveries')
            ->assertSee('Drivers')
            ->assertSee('Trucks')
            ->assertDontSee('Users')
            ->assertDontSee('href="http://localhost/maintenances"', false)
            ->assertDontSee('Activity Logs')
            ->assertDontSee('href="http://localhost/fuel-entries"', false);
    }

    public function test_admin_keeps_full_system_access(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('users.index'))->assertOk();
        $this->actingAs($admin)->get(route('activity-logs.index'))->assertOk();
        $this->actingAs($admin)->get(route('maintenances.index'))->assertOk();
        $this->actingAs($admin)->get(route('fuel-entries.index'))->assertOk();
        $this->actingAs($admin)->get(route('deliveries.index'))->assertOk();
        $this->actingAs($admin)->get(route('trucks.index'))->assertOk();
        $this->actingAs($admin)->get(route('drivers.index'))->assertOk();
    }
}
