<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\ProcurementController;
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
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Pharmacist routes
Route::middleware(['auth', 'role:pharmacist'])->prefix('pharmacist')->group(function () {
    Route::get('/dashboard', [PharmacistController::class, 'dashboard'])->name('pharmacist.dashboard');
    Route::get('/stock', [MedicineController::class, 'index'])->name('pharmacist.stock');
    Route::get('/request', [PharmacistController::class, 'submitRequest'])->name('pharmacist.request');
    Route::get('/expiry', [PharmacistController::class, 'checkExpiry'])->name('pharmacist.expiry');
});

// Procurement Officer routes
Route::middleware(['auth', 'role:procurement'])->prefix('procurement')->group(function () {
    Route::get('/dashboard', [ProcurementController::class, 'dashboard'])->name('procurement.dashboard');
    Route::get('/requests', [ProcurementController::class, 'manageRequests'])->name('procurement.requests');
    Route::get('/stock', [MedicineController::class, 'index'])->name('procurement.stock');
    Route::get('/distribution', [ProcurementController::class, 'recordDistribution'])->name('procurement.distribution');
    Route::get('/reports', [ProcurementController::class, 'viewReports'])->name('procurement.reports');
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
