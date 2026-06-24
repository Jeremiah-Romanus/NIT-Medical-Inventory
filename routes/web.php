<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\LocaleController;

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/audit-trail', [AdminController::class, 'auditTrail'])->name('admin.audit-trail');
    Route::put('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
});

// Pharmacist routes
Route::middleware(['auth', 'role:pharmacist'])->prefix('pharmacist')->group(function () {
    Route::get('/dashboard', [PharmacistController::class, 'dashboard'])->name('pharmacist.dashboard');
    Route::get('/stock', [PharmacistController::class, 'viewStock'])->name('pharmacist.stock');
    Route::get('/procurement-stock', [PharmacistController::class, 'procurementStock'])->name('pharmacist.procurement-stock');
    Route::get('/received', [PharmacistController::class, 'received'])->name('pharmacist.received');
    Route::get('/request', [RequestController::class, 'myRequests'])->name('pharmacist.request');
    Route::get('/expiry', [PharmacistController::class, 'checkExpiry'])->name('pharmacist.expiry');
    Route::get('/reports', [PharmacistController::class, 'reports'])->name('pharmacist.reports');
    Route::get('/reports/print', [PharmacistController::class, 'printReports'])->name('pharmacist.reports.print');
    Route::post('/notifications/read-all', function () {
        auth()->user()?->unreadNotifications?->markAsRead();

        return back()->with('success', 'Notifications marked as read.');
    })->name('pharmacist.notifications.read-all');
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

// Locale switching
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');
