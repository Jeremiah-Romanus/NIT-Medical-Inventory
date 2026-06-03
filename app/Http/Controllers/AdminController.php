<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = now()->toDateString();
        $sixMonthsLater = now()->addMonths(6)->toDateString();

        $stats = [
            'totalUsers' => User::count(),
            'pharmacists' => User::where('role', 'pharmacist')->count(),
            'procurementOfficers' => User::where('role', 'procurement')->count(),
            'totalMedicines' => Medicine::count(),
            'pendingRequests' => MedicineRequest::where('status', 'pending')->count(),
            'totalDistributions' => Distribution::count(),
            'inventoryValue' => (float) (Medicine::query()
                ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total_value')
                ->value('total_value') ?? 0),
            'expiredCount' => Medicine::whereDate('expiry_date', '<', $today)->count(),
            'expiringSoonCount' => Medicine::whereDate('expiry_date', '>=', $today)
                ->whereDate('expiry_date', '<=', $sixMonthsLater)
                ->count(),
        ];

        $recentUsers = User::latest()->limit(6)->get();
        $recentRequests = MedicineRequest::with(['user', 'medicine'])->latest()->limit(6)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentRequests'));
    }

    public function users()
    {
        $users = User::orderByRaw("
            CASE role
                WHEN 'admin' THEN 1
                WHEN 'procurement' THEN 2
                WHEN 'pharmacist' THEN 3
                ELSE 4
            END
        ")->orderBy('name')->get();

        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'pharmacist', 'procurement'])],
        ]);

        if ((int) $request->user()->id === (int) $user->id && $validated['role'] !== 'admin') {
            throw ValidationException::withMessages([
                'role' => 'You cannot remove admin access from your own account while you are logged in.',
            ]);
        }

        $user->forceFill([
            'role' => $validated['role'],
        ])->save();

        return back()->with('success', 'User role updated successfully.');
    }
}
