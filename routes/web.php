<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\MedicineController;

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->intended(match (auth()->user()->role) {
            'admin' => route('admin.dashboard'),
            'pharmacist' => route('pharmacist.dashboard'),
            default => route('procurement.dashboard'),
        });
    }

    return view('welcome');
});

Route::get('/home', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::get('/login/{legacyRole}', fn () => redirect()->route('login'))
    ->whereIn('legacyRole', ['admin', 'pharmacist', 'procurement']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordOtp'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::put('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
});

// Pharmacist routes
Route::middleware(['auth', 'role:pharmacist'])->prefix('pharmacist')->group(function () {
    Route::get('/dashboard', [PharmacistController::class, 'dashboard'])->name('pharmacist.dashboard');
    Route::get('/stock', [MedicineController::class, 'index'])->name('pharmacist.stock');
    Route::get('/request', [RequestController::class, 'myRequests'])->name('pharmacist.request');
    Route::get('/expiry', [PharmacistController::class, 'checkExpiry'])->name('pharmacist.expiry');
});

// Procurement Officer routes
Route::middleware(['auth', 'role:procurement,admin'])->prefix('procurement')->group(function () {
    Route::get('/dashboard', [ProcurementController::class, 'dashboard'])->name('procurement.dashboard');
    Route::get('/requests', [RequestController::class, 'pendingRequests'])->name('procurement.requests');
    Route::get('/stock', [MedicineController::class, 'index'])->name('procurement.stock');
    Route::get('/distribution', [ProcurementController::class, 'recordDistribution'])->name('procurement.distribution');
    Route::post('/distribution', [ProcurementController::class, 'storeDistribution'])->name('procurement.distribution.store');
    Route::get('/reports', [ProcurementController::class, 'viewReports'])->name('procurement.reports');
    Route::get('/reports/export', [ProcurementController::class, 'exportReports'])->name('procurement.reports.export');
    Route::get('/reports/print', [ProcurementController::class, 'printReports'])->name('procurement.reports.print');
});

Route::middleware(['auth', 'role:pharmacist'])->group(function () {
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
});

Route::middleware(['auth', 'role:procurement,admin'])->group(function () {
    Route::post('/requests/{medicineRequest}/approve', [RequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{medicineRequest}/reject', [RequestController::class, 'reject'])->name('requests.reject');
});

// Medicine Routes - Accessible by both roles but with different permissions
Route::middleware(['auth'])->group(function () {
    Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');
    Route::get('/medicines/create', [MedicineController::class, 'create'])->name('medicines.create')->middleware('role:procurement,admin');
    Route::post('/medicines', [MedicineController::class, 'store'])->name('medicines.store')->middleware('role:procurement,admin');
    Route::get('/medicines/{medicine}', [MedicineController::class, 'show'])->name('medicines.show');
    Route::get('/medicines/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit')->middleware('role:procurement,admin');
    Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update')->middleware('role:procurement,admin');
    Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy')->middleware('role:procurement,admin');
});
