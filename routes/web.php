<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PendingApprovalController;
use Illuminate\Support\Facades\Route;

// Public routes - accessible to both guests and authenticated users
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/menu', [ItemController::class, 'menu'])->name('menu');

// Pending approval route (for authenticated users without roles)
Route::middleware('auth')->group(function () {
    Route::get('/pending-approval', [PendingApprovalController::class, 'index'])->name('pending-approval');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Orders routes
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
});

// Routes that require authentication AND a role
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Regular user routes (if any)
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    
    // Dashboard data API (available to all users with roles)
    Route::get('/api/dashboard-data', [DashboardController::class, 'getData'])->name('dashboard.data');
});

// Admin routes that require both authentication and admin role
Route::middleware(['auth', 'admin'])->group(function () {
    // User management
    Route::resource('users', UserController::class);
    
    // Admin routes
    Route::resource('items', ItemController::class);
    Route::resource('categories', CategoryController::class);
    
    // Make sure orders.store is excluded since we defined it in the auth+has.role group
    Route::resource('orders', OrderController::class)->except(['store']);
});

// Authentication routes (automatically added by Breeze)
require __DIR__ . '/auth.php';