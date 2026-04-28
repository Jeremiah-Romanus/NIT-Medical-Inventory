<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use Illuminate\Http\Request;
use App\Models\Medicine;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

        $topDemandMedicines = DB::table('distributions')
            ->join('medicines', 'distributions.medicine_id', '=', 'medicines.id')
            ->select(
                'medicines.name',
                DB::raw('SUM(distributions.quantity_issued) as issued_total')
            )
            ->groupBy('medicines.id', 'medicines.name')
            ->orderByDesc('issued_total')
            ->limit(5)
            ->get();

        $criticalAlerts = Medicine::query()
            ->where(function ($query) use ($today, $threeMonthsLater) {
                $query->where('quantity', '<', 50)
                    ->orWhereDate('expiry_date', '<', $today)
                    ->orWhereDate('expiry_date', '<=', $threeMonthsLater);
            })
            ->orderByRaw("CASE WHEN expiry_date < ? THEN 0 WHEN expiry_date <= ? THEN 1 ELSE 2 END", [$today, $threeMonthsLater])
            ->orderBy('quantity')
            ->limit(5)
            ->get();

        return view('procurement.dashboard', compact('stats', 'recentRequests', 'recentMedicines', 'topDemandMedicines', 'criticalAlerts'));
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
        $medicines = Medicine::orderBy('name')->get();

        $distributions = Distribution::with('medicine')
            ->latest('transaction_date')
            ->limit(10)
            ->get();

        return view('procurement.distribution', compact('distributions', 'medicines'));
    }

    /**
     * Store a distribution record and deduct stock
     */
    public function storeDistribution(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => ['required', 'exists:medicines,id'],
            'distributed_to' => ['required', 'string', 'max:255'],
            'quantity_issued' => ['required', 'integer', 'min:1'],
            'transaction_date' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($validated) {
            $medicine = Medicine::query()
                ->whereKey($validated['medicine_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['quantity_issued'] > $medicine->quantity) {
                throw ValidationException::withMessages([
                    'quantity_issued' => 'Issued quantity exceeds the available stock.',
                ]);
            }

            Distribution::create($validated);

            $medicine->decrement('quantity', $validated['quantity_issued']);
        });

        return redirect()
            ->route('procurement.distribution')
            ->with('success', 'Distribution recorded and stock updated successfully.');
    }

    /**
     * View reports
     */
    public function viewReports()
    {
        [
            'summary' => $summary,
            'topRequested' => $topRequested,
            'topDistributed' => $topDistributed,
            'categories' => $categories,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'category' => $category,
            'distributionTrend' => $distributionTrend,
            'requestTrend' => $requestTrend,
        ] = $this->buildReportData(request());

        return view('procurement.reports', compact(
            'summary',
            'topRequested',
            'topDistributed',
            'categories',
            'startDate',
            'endDate',
            'category',
            'distributionTrend',
            'requestTrend'
        ));
    }

    /**
     * Export report data as CSV
     */
    public function exportReports(Request $request): StreamedResponse
    {
        [
            'summary' => $summary,
            'topRequested' => $topRequested,
            'topDistributed' => $topDistributed,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'category' => $category,
        ] = $this->buildReportData($request);

        $filename = 'inventory-report-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use (
            $summary,
            $topRequested,
            $topDistributed,
            $startDate,
            $endDate,
            $category
        ) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Medical Inventory and Distribution Report']);
            fputcsv($handle, ['Start Date', $startDate ?: 'All']);
            fputcsv($handle, ['End Date', $endDate ?: 'All']);
            fputcsv($handle, ['Category', $category ?: 'All']);
            fputcsv($handle, []);

            fputcsv($handle, ['Summary']);
            fputcsv($handle, ['Metric', 'Value']);
            fputcsv($handle, ['Total Medicines', $summary['totalMedicines']]);
            fputcsv($handle, ['Expired Medicines', $summary['expiredCount']]);
            fputcsv($handle, ['Expiring Soon', $summary['expiringSoonCount']]);
            fputcsv($handle, ['Low Stock Medicines', $summary['lowStockCount']]);
            fputcsv($handle, ['Inventory Value (TZS)', $summary['inventoryValue']]);
            fputcsv($handle, []);

            fputcsv($handle, ['Top Requested Medicines']);
            fputcsv($handle, ['Medicine', 'Category', 'Total Requested']);
            foreach ($topRequested as $medicine) {
                fputcsv($handle, [$medicine->name, $medicine->category, $medicine->total_requested]);
            }
            fputcsv($handle, []);

            fputcsv($handle, ['Top Distributed Medicines']);
            fputcsv($handle, ['Medicine', 'Category', 'Total Issued']);
            foreach ($topDistributed as $medicine) {
                fputcsv($handle, [$medicine->name, $medicine->category, $medicine->total_issued]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Open a print-friendly report page for Save as PDF
     */
    public function printReports(Request $request)
    {
        [
            'summary' => $summary,
            'topRequested' => $topRequested,
            'topDistributed' => $topDistributed,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'category' => $category,
            'distributionTrend' => $distributionTrend,
            'requestTrend' => $requestTrend,
        ] = $this->buildReportData($request);

        return view('procurement.reports-print', compact(
            'summary',
            'topRequested',
            'topDistributed',
            'startDate',
            'endDate',
            'category',
            'distributionTrend',
            'requestTrend'
        ));
    }

    protected function buildReportData(Request $request): array
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $category = $request->query('category');

        $medicinesQuery = Medicine::query();

        if ($category) {
            $medicinesQuery->where('category', $category);
        }

        $medicines = $medicinesQuery->get();
        $summary = [
            'totalMedicines' => $medicines->count(),
            'expiredCount' => $medicines->filter(fn ($medicine) => $medicine->isExpired())->count(),
            'expiringSoonCount' => $medicines->filter(fn ($medicine) => $medicine->isExpiringSoon())->count(),
            'lowStockCount' => $medicines->where('quantity', '<', 50)->count(),
            'inventoryValue' => (float) $medicines->sum(fn ($medicine) => $medicine->quantity * $medicine->unit_price),
        ];

        $topRequested = DB::table('requests')
            ->join('medicines', 'requests.medicine_id', '=', 'medicines.id')
            ->when($category, fn ($query) => $query->where('medicines.category', $category))
            ->when($startDate, fn ($query) => $query->whereDate('requests.created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('requests.created_at', '<=', $endDate))
            ->select(
                'medicines.name',
                'medicines.category',
                DB::raw('SUM(requests.requested_quantity) as total_requested')
            )
            ->groupBy('medicines.id', 'medicines.name', 'medicines.category')
            ->orderByDesc('total_requested')
            ->limit(5)
            ->get();

        $topDistributed = DB::table('distributions')
            ->join('medicines', 'distributions.medicine_id', '=', 'medicines.id')
            ->when($category, fn ($query) => $query->where('medicines.category', $category))
            ->when($startDate, fn ($query) => $query->whereDate('distributions.transaction_date', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('distributions.transaction_date', '<=', $endDate))
            ->select(
                'medicines.name',
                'medicines.category',
                DB::raw('SUM(distributions.quantity_issued) as total_issued')
            )
            ->groupBy('medicines.id', 'medicines.name', 'medicines.category')
            ->orderByDesc('total_issued')
            ->limit(5)
            ->get();

        $distributionTrend = DB::table('distributions')
            ->join('medicines', 'distributions.medicine_id', '=', 'medicines.id')
            ->when($category, fn ($query) => $query->where('medicines.category', $category))
            ->when($startDate, fn ($query) => $query->whereDate('distributions.transaction_date', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('distributions.transaction_date', '<=', $endDate))
            ->selectRaw('DATE(distributions.transaction_date) as day, SUM(distributions.quantity_issued) as total_issued')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $requestTrend = DB::table('requests')
            ->join('medicines', 'requests.medicine_id', '=', 'medicines.id')
            ->when($category, fn ($query) => $query->where('medicines.category', $category))
            ->when($startDate, fn ($query) => $query->whereDate('requests.created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('requests.created_at', '<=', $endDate))
            ->selectRaw('DATE(requests.created_at) as day, SUM(requests.requested_quantity) as total_requested')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $categories = Medicine::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return compact(
            'summary',
            'topRequested',
            'topDistributed',
            'categories',
            'startDate',
            'endDate',
            'category',
            'distributionTrend',
            'requestTrend'
        );
    }
}
