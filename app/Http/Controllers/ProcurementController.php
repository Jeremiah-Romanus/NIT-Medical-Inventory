<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;

class ProcurementController extends Controller
{
    /**
     * Show procurement officer dashboard
     */
    public function dashboard()
    {
        $today = now()->toDateString();
        $threeMonthsLater = now()->addMonths(3)->toDateString();

        $stats = [
            'totalMedicines' => Medicine::count(),
            'pendingRequests' => DB::table('requests')->where('status', 'pending')->count(),
            'todayDistributions' => DB::table('distributions')->whereDate('transaction_date', $today)->count(),
            'inventoryValue' => (float) (Medicine::query()
                ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total_value')
                ->value('total_value') ?? 0),
            'lowStockCount' => Medicine::where('quantity', '<', 50)->count(),
            'expiredCount' => Medicine::whereDate('expiry_date', '<', $today)->count(),
            'expiringSoonCount' => Medicine::whereDate('expiry_date', '>=', $today)
                ->whereDate('expiry_date', '<=', $threeMonthsLater)
                ->count(),
        ];

        $recentRequests = DB::table('requests')
            ->join('users', 'requests.user_id', '=', 'users.id')
            ->join('medicines', 'requests.medicine_id', '=', 'medicines.id')
            ->select(
                'requests.id',
                'users.name as requester',
                'medicines.name as medicine',
                'requests.requested_quantity as quantity',
                'requests.status',
                'requests.created_at'
            )
            ->orderByDesc('requests.created_at')
            ->limit(5)
            ->get();

        $recentMedicines = Medicine::orderByDesc('updated_at')->limit(5)->get();

        return view('procurement.dashboard', compact('stats', 'recentRequests', 'recentMedicines'));
    }

    /**
     * Approve or reject requests
     */
    public function manageRequests()
    {
        $requests = DB::table('requests')
            ->join('users', 'requests.user_id', '=', 'users.id')
            ->join('medicines', 'requests.medicine_id', '=', 'medicines.id')
            ->select(
                'requests.id',
                'users.name as requester',
                'medicines.name as medicine',
                'requests.requested_quantity as quantity',
                'requests.status',
                'requests.remarks',
                'requests.created_at'
            )
            ->orderByDesc('requests.created_at')
            ->get();

        return view('procurement.requests', compact('requests'));
    }

    /**
     * Update stock
     */
    public function updateStock()
    {
        $medicines = Medicine::orderBy('name')->get();

        return view('medicines.index', compact('medicines'));
    }

    /**
     * Record medicine distribution
     */
    public function recordDistribution()
    {
        $distributions = DB::table('distributions')
            ->join('medicines', 'distributions.medicine_id', '=', 'medicines.id')
            ->select(
                'distributions.id',
                'medicines.name as medicine',
                'distributions.distributed_to',
                'distributions.quantity_issued',
                'distributions.transaction_date'
            )
            ->orderByDesc('distributions.transaction_date')
            ->limit(10)
            ->get();

        return view('procurement.distribution', compact('distributions'));
    }

    /**
     * View reports
     */
    public function viewReports()
    {
        $medicines = Medicine::all();
        $summary = [
            'totalMedicines' => $medicines->count(),
            'expiredCount' => $medicines->filter(fn ($medicine) => $medicine->isExpired())->count(),
            'expiringSoonCount' => $medicines->filter(fn ($medicine) => $medicine->isExpiringSoon())->count(),
            'lowStockCount' => $medicines->where('quantity', '<', 50)->count(),
            'inventoryValue' => (float) $medicines->sum(fn ($medicine) => $medicine->quantity * $medicine->unit_price),
        ];

        return view('procurement.reports', compact('summary'));
    }
}
