<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Delivery;
use App\Models\Truck;
use App\Models\Driver;
use App\Models\User;

use App\Models\ActivityLog;

class DashboardController extends Controller
{
    /**
     * Redirect users based on their role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isDriver()) {
            return view('dashboard.driver', $this->getDriverStats($user));
        }

        $stats = $this->getAdminStats();
        
        if ($user->isAdmin()) {
            return view('dashboard.admin', $stats);
        }

        if ($user->isDispatcher()) {
            return view('dashboard.dispatcher', $stats);
        }

        abort(403);
    }

    private function getAdminStats(): array
    {
        $totalDeliveries = Delivery::count();
        $completedDeliveries = Delivery::where('status', 'completed')->count();
        
        $recentLogs = collect();
        if (Schema::hasTable('activity_logs')) {
            $logsQuery = ActivityLog::with('user')->latest();
            if (auth()->user()->isDispatcher()) {
                $logsQuery->whereNotIn('action', ['user_created', 'user_updated', 'user_deleted', 'user_role_changed']);
            }
            $recentLogs = $logsQuery->take(5)->get();
        }

        return [
            'stats' => [
                'total_deliveries' => $totalDeliveries,
                'active_deliveries' => Delivery::where('status', 'in_progress')->count(),
                'completed_deliveries' => $completedDeliveries,
                'available_trucks' => Truck::where('status', 'available')->count(),
                'trucks_on_delivery' => Truck::where('status', 'on_delivery')->count(),
                'available_drivers' => Driver::where('status', 'available')->count(),
                'busy_drivers' => Driver::where('status', 'busy')->count(),
                'completion_rate' => $totalDeliveries > 0 ? round(($completedDeliveries / $totalDeliveries) * 100) : 0,
            ],
            'recent_deliveries' => Delivery::with(['truck', 'driver'])->latest()->take(5)->get(),
            'recent_logs' => $recentLogs,
            'chart_data' => [
                'delivery_status' => [
                    'pending' => Delivery::where('status', 'pending')->count(),
                    'in_progress' => Delivery::where('status', 'in_progress')->count(),
                    'completed' => Delivery::where('status', 'completed')->count(),
                ],
                'truck_utilization' => [
                    'available' => Truck::where('status', 'available')->count(),
                    'on_delivery' => Truck::where('status', 'on_delivery')->count(),
                    'maintenance' => Truck::where('status', 'maintenance')->count(),
                ],
                'driver_availability' => [
                    'available' => Driver::where('status', 'available')->count(),
                    'busy' => Driver::where('status', 'busy')->count(),
                    'inactive' => Driver::where('status', 'inactive')->count(),
                ],
            ]
        ];
    }

    private function getDriverStats($user): array
    {
        $driverProfile = $user->driver;
        return [
            'my_deliveries_count' => $driverProfile ? $driverProfile->deliveries()->count() : 0,
            'active_deliveries' => $driverProfile ? $driverProfile->deliveries()->where('status', 'in_progress')->get() : collect(),
            'completed_deliveries_count' => $driverProfile ? $driverProfile->deliveries()->where('status', 'completed')->count() : 0,
        ];
    }
}
