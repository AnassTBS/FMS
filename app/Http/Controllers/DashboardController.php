<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect users based on their role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return view('dashboard.admin');
        }

        if ($user->isDispatcher()) {
            return view('dashboard.dispatcher');
        }

        if ($user->isDriver()) {
            return view('dashboard.driver');
        }

        abort(403);
    }
}
