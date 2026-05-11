<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;



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
