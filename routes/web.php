<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\MedicineController;

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect(auth()->user()->role === 'pharmacist'
            ? route('pharmacist.dashboard')
            : route('procurement.dashboard'));
    }

    return view('welcome');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginSelection'])->name('login');
Route::get('/login/{role}', [AuthController::class, 'showLogin'])->name('login.role');
Route::post('/login/{role}', [AuthController::class, 'login'])->name('login.attempt');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Pharmacist routes
Route::middleware(['auth', 'role:pharmacist'])->prefix('pharmacist')->group(function () {
    Route::get('/dashboard', [PharmacistController::class, 'dashboard'])->name('pharmacist.dashboard');
    Route::get('/stock', [MedicineController::class, 'index'])->name('pharmacist.stock');
    Route::get('/request', [RequestController::class, 'myRequests'])->name('pharmacist.request');
    Route::get('/expiry', [PharmacistController::class, 'checkExpiry'])->name('pharmacist.expiry');
});

// Procurement Officer routes
Route::middleware(['auth', 'role:procurement'])->prefix('procurement')->group(function () {
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

Route::middleware(['auth', 'role:procurement'])->group(function () {
    Route::post('/requests/{medicineRequest}/approve', [RequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{medicineRequest}/reject', [RequestController::class, 'reject'])->name('requests.reject');
});

// Medicine Routes - Accessible by both roles but with different permissions
Route::middleware(['auth'])->group(function () {
    Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');
    Route::get('/medicines/create', [MedicineController::class, 'create'])->name('medicines.create')->middleware('role:procurement');
    Route::post('/medicines', [MedicineController::class, 'store'])->name('medicines.store')->middleware('role:procurement');
    Route::get('/medicines/{medicine}', [MedicineController::class, 'show'])->name('medicines.show');
    Route::get('/medicines/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit')->middleware('role:procurement');
    Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update')->middleware('role:procurement');
    Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy')->middleware('role:procurement');
});
