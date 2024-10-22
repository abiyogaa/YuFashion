<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['admin'])->group(function () {
    Route::get('/admin', function () {
        return 'Admin Dashboard';
    });
});

Route::get('/', function () {
    return redirect()->route('login');
});
