<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageCategoryController;
use App\Http\Controllers\Admin\ManageClothingItemController;
use App\Http\Controllers\Admin\ManageRentalController;
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\RentalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

// Root route
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role->name === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::user()->role->name === 'user') {
            return redirect()->route('user.dashboard');
        }
    }
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
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('admin/categories', ManageCategoryController::class);
        Route::resource('admin/clothing_items', ManageClothingItemController::class);
        Route::resource('admin/users', ManageUserController::class);
        Route::get('admin/users/{id}/print-rental-history', [ManageUserController::class, 'printRentalHistory'])->name('admin.users.print-rental-history');
        
        Route::get('admin/rentals', [ManageRentalController::class, 'index'])->name('admin.rentals.index');
        Route::put('admin/rentals/{id}/approve', [ManageRentalController::class, 'approve'])->name('admin.rentals.approve');
        Route::put('admin/rentals/{id}/reject', [ManageRentalController::class, 'reject'])->name('admin.rentals.reject');
        Route::put('admin/rentals/{id}/return', [ManageRentalController::class, 'return'])->name('admin.rentals.return');
    });

    // User routes
    Route::middleware('role:user')->group(function () {
        Route::get('user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

        Route::get('user/rent/{clothing_item_id}', [RentalController::class, 'create'])->name('rent.form');
        Route::post('user/rent', [RentalController::class, 'store'])->name('rent.store');
        Route::get('user/rentals', [RentalController::class, 'index'])->name('user.rentals');

    });
    
});

// Redirect to root if user accessed wrong route
Route::fallback(function () {
    return redirect('/');
});
