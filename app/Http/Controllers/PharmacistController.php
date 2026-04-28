<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PharmacistController extends Controller
{
    /**
     * Show pharmacist dashboard
     */
    public function dashboard()
    {
        $today = now()->toDateString();
        $threeMonthsLater = now()->addMonths(3)->toDateString();

        $stats = [
            'totalMedicines' => Medicine::count(),
            'lowStockCount' => Medicine::where('quantity', '<', 50)->count(),
            'expiredCount' => Medicine::whereDate('expiry_date', '<', $today)->count(),
            'expiringSoonCount' => Medicine::whereDate('expiry_date', '>=', $today)
                ->whereDate('expiry_date', '<=', $threeMonthsLater)
                ->count(),
            'pendingRequests' => DB::table('requests')->where('user_id', Auth::id())->where('status', 'pending')->count(),
            'approvedRequests' => DB::table('requests')->where('user_id', Auth::id())->where('status', 'approved')->count(),
            'rejectedRequests' => DB::table('requests')->where('user_id', Auth::id())->where('status', 'rejected')->count(),
        ];

        $recentRequests = DB::table('requests')
            ->join('medicines', 'requests.medicine_id', '=', 'medicines.id')
            ->select(
                'requests.id',
                'medicines.name as medicine',
                'requests.requested_quantity as quantity',
                'requests.status',
                'requests.created_at'
            )
            ->where('requests.user_id', Auth::id())
            ->orderByDesc('requests.created_at')
            ->limit(5)
            ->get();

        $expiringMedicines = Medicine::whereDate('expiry_date', '>=', $today)
            ->whereDate('expiry_date', '<=', $threeMonthsLater)
            ->orderBy('expiry_date')
            ->limit(5)
            ->get();

        $criticalMedicines = Medicine::query()
            ->where(function ($query) use ($today, $threeMonthsLater) {
                $query->where('quantity', '<', 50)
                    ->orWhereDate('expiry_date', '<', $today)
                    ->orWhereDate('expiry_date', '<=', $threeMonthsLater);
            })
            ->orderByRaw("CASE WHEN expiry_date < ? THEN 0 WHEN expiry_date <= ? THEN 1 ELSE 2 END", [$today, $threeMonthsLater])
            ->orderBy('quantity')
            ->limit(5)
            ->get();

        return view('pharmacist.dashboard', compact('stats', 'recentRequests', 'expiringMedicines', 'criticalMedicines'));
    }

    /**
     * View stock
     */
    public function viewStock()
    {
        $medicines = Medicine::orderBy('name')->get();

        return view('medicines.index', compact('medicines'));
    }

    /**
     * Submit medicine request
     */
    public function submitRequest()
    {
        $medicines = Medicine::orderBy('name')->get();

        return view('pharmacist.request', compact('medicines'));
    }

    /**
     * Check expiry dates
     */
    public function checkExpiry()
    {
        $medicines = Medicine::orderBy('expiry_date')->get();

        return view('pharmacist.expiry', compact('medicines'));
    }
}
