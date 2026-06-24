<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\User;
use App\Models\AuditLog;
use App\Support\AuditTrail;
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
            'auditLogs' => AuditLog::count(),
            'inventoryValue' => (float) (Medicine::query()
                ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total_value')
                ->value('total_value') ?? 0),
            'expiredCount' => Medicine::whereDate('expiry_date', '<', $today)->count(),
            'expiringSoonCount' => Medicine::whereDate('expiry_date', '>=', $today)
                ->whereDate('expiry_date', '<=', $sixMonthsLater)
                ->count(),
            'lowStockCount' => Medicine::where('pharmacy_quantity', '<=', 30)->count(),
        ];

        $recentUsers = User::latest()->limit(6)->get();
        $recentRequests = MedicineRequest::with(['user', 'medicine'])->latest()->limit(6)->get();
        $recentAuditLogs = AuditLog::with('user')->latest()->limit(6)->get();

        $lowStockMedicines = Medicine::where('pharmacy_quantity', '<=', 30)
            ->orderBy('pharmacy_quantity')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentRequests', 'recentAuditLogs', 'lowStockMedicines'));
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

    public function auditTrail(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('q')) {
            $term = $request->input('q');
            $query->where(function ($builder) use ($term) {
                $builder->where('action', 'like', '%' . $term . '%')
                    ->orWhere('subject', 'like', '%' . $term . '%')
                    ->orWhereHas('user', function ($userQuery) use ($term) {
                        $userQuery->where('name', 'like', '%' . $term . '%')
                            ->orWhere('email', 'like', '%' . $term . '%');
                    });
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        $actions = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $logs = $query->paginate(25)->withQueryString();

        return view('admin.audit-trail', compact('logs', 'actions'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'pharmacist', 'procurement'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ((int) $request->user()->id === (int) $user->id && $validated['role'] !== 'admin') {
            throw ValidationException::withMessages([
                'role' => 'You cannot remove admin access from your own account while you are logged in.',
            ]);
        }

        $oldRole = $user->role;

        $user->forceFill([
            'role' => $validated['role'],
        ]);

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        if ($oldRole !== $user->role) {
            AuditTrail::record(
                'user.role_updated',
                $user,
                $user->name,
                ['role' => $oldRole],
                ['role' => $user->role]
            );
        }

        if (!empty($validated['password'])) {
            AuditTrail::record(
                'user.password_updated',
                $user,
                $user->name,
                [],
                []
            );
        }

        return back()->with('success', 'User updated successfully.');
    }
}
