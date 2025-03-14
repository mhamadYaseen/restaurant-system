<?php
// filepath: g:\projects\restaurant-system\routes\web.php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public routes - only accessible to both guests and authenticated users


Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/menu', [ItemController::class, 'menu'])->name('menu');

// Routes that require authentication
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Regular user routes (if any)
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Admin routes that require both authentication and admin role
    Route::middleware('admin')->group(function () {
        // Admin routes
        Route::resource('items', ItemController::class);
        Route::resource('categories', CategoryController::class);

        // Make sure orders.store is excluded since we defined it in the auth group
        Route::resource('orders', OrderController::class)->except(['store']);
    });

    Route::get('/api/dashboard-data', [DashboardController::class, 'getData'])->name('dashboard.data');
});

// Authentication routes (automatically added by Breeze)
require __DIR__ . '/auth.php';
