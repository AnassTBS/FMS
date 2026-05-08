<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ActivityLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin|dispatcher'),
        ];
    }

    /**
     * Display a listing of the activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Dispatchers can only see operational logs (excluding user management)
        if (auth()->user()->isDispatcher()) {
            $query->whereNotIn('action', ['user_created', 'user_updated', 'user_deleted', 'user_role_changed']);
        }

        $logs = $query->paginate(20);
        $users = User::orderBy('name')->get();

        return view('activity_logs.index', compact('logs', 'users'));
    }
}
