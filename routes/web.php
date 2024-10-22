<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageCategoryController;
use App\Http\Controllers\Admin\ManageClothingItemController;
use App\Http\Controllers\Admin\ManageRentalController;
use App\Http\Controllers\Admin\ManageUserController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('admin/categories', ManageCategoryController::class);
        Route::resource('admin/clothing_items', ManageClothingItemController::class);
        Route::resource('admin/users', ManageUserController::class);
        
        Route::get('admin/rentals', [ManageRentalController::class, 'index'])->name('admin.rentals.index');
        Route::post('admin/rentals/{id}/approve', [ManageRentalController::class, 'approve'])->name('admin.rentals.approve');
        Route::post('admin/rentals/{id}/reject', [ManageRentalController::class, 'reject'])->name('admin.rentals.reject');
    });
});
