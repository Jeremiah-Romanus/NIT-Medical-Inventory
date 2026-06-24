<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineRequest;
use Illuminate\Http\Request;
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
        $sixMonthsLater = now()->addMonths(6)->toDateString();

        $stats = [
            'totalMedicines' => Medicine::count(),
            'lowStockCount' => Medicine::where('pharmacy_quantity', '<=', 30)->count(),
            'expiredCount' => Medicine::whereDate('expiry_date', '<', $today)->count(),
            'expiringSoonCount' => Medicine::whereDate('expiry_date', '>=', $today)
                ->whereDate('expiry_date', '<=', $sixMonthsLater)
                ->count(),
            'pendingRequests' => DB::table('requests')->where('user_id', Auth::id())->where('status', 'pending')->count(),
            'approvedRequests' => DB::table('requests')->where('user_id', Auth::id())->where('status', 'approved')->count(),
            'rejectedRequests' => DB::table('requests')->where('user_id', Auth::id())->where('status', 'rejected')->count(),
            'pharmacyStockMedicines' => Medicine::where('pharmacy_quantity', '>', 0)->count(),
            'pharmacyStockUnits' => (int) Medicine::sum('pharmacy_quantity'),
            'procurementStockMedicines' => Medicine::where('quantity', '>', 0)->count(),
            'procurementStockUnits' => (int) Medicine::sum('quantity'),
        ];

        $pharmacyStockPreview = Medicine::where('pharmacy_quantity', '>', 0)
            ->orderByDesc('pharmacy_quantity')
            ->limit(5)
            ->get();

        $procurementStockPreview = Medicine::where('quantity', '>', 0)
            ->orderByDesc('quantity')
            ->limit(5)
            ->get();

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

        $lowStockMedicines = Medicine::where('pharmacy_quantity', '<=', 30)
            ->orderBy('pharmacy_quantity')
            ->limit(6)
            ->get();

        $notifications = Auth::user()
            ?->unreadNotifications()
            ->latest()
            ->take(5)
            ->get() ?? collect();

        return view('pharmacist.dashboard', compact(
            'stats',
            'recentRequests',
            'lowStockMedicines',
            'pharmacyStockPreview',
            'procurementStockPreview',
            'notifications'
        ));
    }

    /**
     * View stock
     */
    public function viewStock()
    {
        $approvedMedicines = MedicineRequest::with(['medicine', 'approver'])
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->orderByDesc('approved_at')
            ->get();

        return view('pharmacist.stock', compact('approvedMedicines'));
    }

    /**
     * View procurement stock available for request planning
     */
    public function procurementStock()
    {
        $medicines = Medicine::where('quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('pharmacist.procurement-stock', compact('medicines'));
    }

    /**
     * Show medicines that were approved for the current pharmacist (received from procurement)
     */
    public function received()
    {
        $receivedRequests = MedicineRequest::with(['medicine', 'approver'])
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->orderByDesc('approved_at')
            ->get();

        return view('pharmacist.received', compact('receivedRequests'));
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

    /**
     * Show pharmacist report analytics
     */
    public function reports(Request $request)
    {
        [
            'summary' => $summary,
            'topRequestedMedicines' => $topRequestedMedicines,
            'approvedMedicines' => $approvedMedicines,
            'requestTrend' => $requestTrend,
            'recentRequests' => $recentRequests,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ] = $this->buildReportData($request);

        return view('pharmacist.reports', compact(
            'summary',
            'topRequestedMedicines',
            'approvedMedicines',
            'requestTrend',
            'recentRequests',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Print-friendly pharmacist report
     */
    public function printReports(Request $request)
    {
        [
            'summary' => $summary,
            'topRequestedMedicines' => $topRequestedMedicines,
            'approvedMedicines' => $approvedMedicines,
            'requestTrend' => $requestTrend,
            'recentRequests' => $recentRequests,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ] = $this->buildReportData($request);

        return view('pharmacist.reports-print', compact(
            'summary',
            'topRequestedMedicines',
            'approvedMedicines',
            'requestTrend',
            'recentRequests',
            'startDate',
            'endDate'
        ));
    }

    protected function buildReportData(Request $request): array
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $requestQuery = MedicineRequest::query()
            ->where('user_id', Auth::id())
            ->when($startDate, fn ($query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('created_at', '<=', $endDate));

        $summary = [
            'totalRequests' => (clone $requestQuery)->count(),
            'pendingRequests' => (clone $requestQuery)->where('status', 'pending')->count(),
            'approvedRequests' => (clone $requestQuery)->where('status', 'approved')->count(),
            'rejectedRequests' => (clone $requestQuery)->where('status', 'rejected')->count(),
            'requestedUnits' => (int) (clone $requestQuery)->sum('requested_quantity'),
            'approvedUnits' => (int) (clone $requestQuery)->where('status', 'approved')->sum('requested_quantity'),
            'rejectedUnits' => (int) (clone $requestQuery)->where('status', 'rejected')->sum('requested_quantity'),
            'approvedMedicineLines' => (clone $requestQuery)->where('status', 'approved')->distinct()->count('medicine_id'),
            'pharmacyStockMedicines' => Medicine::where('pharmacy_quantity', '>', 0)->count(),
            'pharmacyStockUnits' => (int) Medicine::sum('pharmacy_quantity'),
            'lowStockLines' => Medicine::where('pharmacy_quantity', '<=', 30)->count(),
        ];

        $topRequestedMedicines = DB::table('requests')
            ->join('medicines', 'requests.medicine_id', '=', 'medicines.id')
            ->when($startDate, fn ($query) => $query->whereDate('requests.created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('requests.created_at', '<=', $endDate))
            ->where('requests.user_id', Auth::id())
            ->select(
                'medicines.medical_id',
                'medicines.name',
                'medicines.formulation_strength',
                DB::raw('SUM(requests.requested_quantity) as total_requested'),
                DB::raw('SUM(CASE WHEN requests.status = "approved" THEN requests.requested_quantity ELSE 0 END) as total_approved'),
                DB::raw('SUM(CASE WHEN requests.status = "rejected" THEN requests.requested_quantity ELSE 0 END) as total_rejected')
            )
            ->groupBy('medicines.id', 'medicines.medical_id', 'medicines.name', 'medicines.formulation_strength')
            ->orderByDesc('total_requested')
            ->limit(8)
            ->get();

        $approvedMedicines = DB::table('requests')
            ->join('medicines', 'requests.medicine_id', '=', 'medicines.id')
            ->when($startDate, fn ($query) => $query->whereDate('requests.created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('requests.created_at', '<=', $endDate))
            ->where('requests.user_id', Auth::id())
            ->where('requests.status', 'approved')
            ->select(
                'medicines.medical_id',
                'medicines.name',
                'medicines.formulation_strength',
                'medicines.batch_number',
                'medicines.pharmacy_quantity',
                DB::raw('SUM(requests.requested_quantity) as total_approved'),
                DB::raw('MAX(requests.approved_at) as last_approved_at')
            )
            ->groupBy(
                'medicines.id',
                'medicines.medical_id',
                'medicines.name',
                'medicines.formulation_strength',
                'medicines.batch_number',
                'medicines.pharmacy_quantity'
            )
            ->orderByDesc('last_approved_at')
            ->limit(8)
            ->get();

        $requestTrend = DB::table('requests')
            ->where('user_id', Auth::id())
            ->when($startDate, fn ($query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('created_at', '<=', $endDate))
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total_requests, SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_count, SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_count')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(function ($item) {
                $item->day = \App\Helpers\DateHelper::formatDate($item->day);
                return $item;
            });

        $recentRequests = MedicineRequest::with('medicine')
            ->where('user_id', Auth::id())
            ->when($startDate, fn ($query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('created_at', '<=', $endDate))
            ->latest()
            ->limit(10)
            ->get();

        return compact(
            'summary',
            'topRequestedMedicines',
            'approvedMedicines',
            'requestTrend',
            'recentRequests',
            'startDate',
            'endDate'
        );
    }
}
