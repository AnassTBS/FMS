<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\FuelEntryController;
use App\Http\Controllers\MaintenanceController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard & Role Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Only
    Route::resource('users', UserController::class)->middleware('can:manage-users');

    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->middleware('can:view-activity-logs')
        ->name('activity-logs.index');

    // Fuel Entries
    Route::resource('fuel-entries', FuelEntryController::class)->middleware('can:manage-fuel-entries');

    // Maintenances
    Route::resource('maintenances', MaintenanceController::class)->middleware('can:manage-maintenance');

    // Deliveries (Accessible by Admin and Dispatcher, restricted view for Driver)
    Route::resource('deliveries', DeliveryController::class);
    Route::resource('trucks', TruckController::class);
    Route::resource('drivers', DriverController::class);
});
