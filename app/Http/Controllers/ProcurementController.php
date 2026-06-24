<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Support\AuditTrail;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ProcurementController extends Controller
{
    /**
     * Show procurement officer dashboard
     */
    public function dashboard()
    {
        $today = now()->toDateString();
        $sixMonthsLater = now()->addMonths(6)->toDateString();

        $stats = [
            'totalMedicines' => Medicine::count(),
            'pendingRequests' => DB::table('requests')->where('status', 'pending')->count(),
            'todayDistributions' => DB::table('distributions')->whereDate('transaction_date', $today)->count(),
            'inventoryValue' => (float) (Medicine::query()
                ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total_value')
                ->value('total_value') ?? 0),
            'lowStockCount' => Medicine::where('quantity', '<=', 30)->count(),
            'expiredCount' => Medicine::whereDate('expiry_date', '<', $today)->count(),
            'expiringSoonCount' => Medicine::whereDate('expiry_date', '>=', $today)
                ->whereDate('expiry_date', '<=', $sixMonthsLater)
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
            ->where(function ($query) use ($today, $sixMonthsLater) {
                $query->where('quantity', '<=', 30)
                    ->orWhereDate('expiry_date', '<', $today)
                    ->orWhereDate('expiry_date', '<=', $sixMonthsLater);
            })
            ->orderByRaw("CASE WHEN expiry_date < ? THEN 0 WHEN expiry_date <= ? THEN 1 ELSE 2 END", [$today, $sixMonthsLater])
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
            'transaction_date' => ['required', 'date_format:Y-m-d\TH:i'],
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

            // Convert datetime-local format to proper datetime
            $validated['transaction_date'] = Carbon::createFromFormat('Y-m-d\TH:i', $validated['transaction_date'])->format('Y-m-d H:i:00');

            $oldQuantity = $medicine->quantity;
            $distribution = Distribution::create($validated);

            $medicine->decrement('quantity', $validated['quantity_issued']);
            $medicine->refresh();

            AuditTrail::record(
                'distribution.created',
                $distribution,
                $medicine->name,
                ['medicine_quantity' => $oldQuantity],
                [
                    'medicine_quantity' => $medicine->quantity,
                    'distributed_to' => $distribution->distributed_to,
                    'quantity_issued' => $distribution->quantity_issued,
                    'transaction_date' => $distribution->transaction_date?->toDateTimeString(),
                ],
                ['medicine_id' => $medicine->id]
            );
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
            'startDate' => $startDate,
            'endDate' => $endDate,
            'distributionTrend' => $distributionTrend,
            'requestTrend' => $requestTrend,
        ] = $this->buildReportData(request());

        return view('procurement.reports', compact(
            'summary',
            'topRequested',
            'topDistributed',
            'startDate',
            'endDate',
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
        ] = $this->buildReportData($request);

        $filename = 'inventory-report-' . now()->format('d-m-Y-His') . '.csv';

        return response()->streamDownload(function () use (
            $summary,
            $topRequested,
            $topDistributed,
            $startDate,
            $endDate
        ) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Medical Inventory and Distribution Report']);
            fputcsv($handle, ['Start Date', $startDate ?: 'All']);
            fputcsv($handle, ['End Date', $endDate ?: 'All']);
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
            fputcsv($handle, ['Medical ID', 'Generic Name', 'Formulation / Strength', 'Total Requested']);
            foreach ($topRequested as $medicine) {
                fputcsv($handle, [$medicine->medical_id, $medicine->name, $medicine->formulation_strength, $medicine->total_requested]);
            }
            fputcsv($handle, []);

            fputcsv($handle, ['Top Distributed Medicines']);
            fputcsv($handle, ['Medical ID', 'Generic Name', 'Formulation / Strength', 'Total Issued']);
            foreach ($topDistributed as $medicine) {
                fputcsv($handle, [$medicine->medical_id, $medicine->name, $medicine->formulation_strength, $medicine->total_issued]);
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
            'distributionTrend' => $distributionTrend,
            'requestTrend' => $requestTrend,
        ] = $this->buildReportData($request);

        return view('procurement.reports-print', compact(
            'summary',
            'topRequested',
            'topDistributed',
            'startDate',
            'endDate',
            'distributionTrend',
            'requestTrend'
        ));
    }

    protected function buildReportData(Request $request): array
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $medicines = Medicine::query()->get();
        $summary = [
            'totalMedicines' => $medicines->count(),
            'expiredCount' => $medicines->filter(fn ($medicine) => $medicine->isExpired())->count(),
            'expiringSoonCount' => $medicines->filter(fn ($medicine) => $medicine->isExpiringSoon())->count(),
            'lowStockCount' => $medicines->where('quantity', '<', 50)->count(),
            'inventoryValue' => (float) $medicines->sum(fn ($medicine) => $medicine->quantity * $medicine->unit_price),
        ];

        $topRequested = DB::table('requests')
            ->join('medicines', 'requests.medicine_id', '=', 'medicines.id')
            ->when($startDate, fn ($query) => $query->whereDate('requests.created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('requests.created_at', '<=', $endDate))
            ->select(
                'medicines.medical_id',
                'medicines.name',
                'medicines.formulation_strength',
                DB::raw('SUM(requests.requested_quantity) as total_requested')
            )
            ->groupBy('medicines.id', 'medicines.medical_id', 'medicines.name', 'medicines.formulation_strength')
            ->orderByDesc('total_requested')
            ->limit(5)
            ->get();

        $topDistributed = DB::table('distributions')
            ->join('medicines', 'distributions.medicine_id', '=', 'medicines.id')
            ->when($startDate, fn ($query) => $query->whereDate('distributions.transaction_date', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('distributions.transaction_date', '<=', $endDate))
            ->select(
                'medicines.medical_id',
                'medicines.name',
                'medicines.formulation_strength',
                DB::raw('SUM(distributions.quantity_issued) as total_issued')
            )
            ->groupBy('medicines.id', 'medicines.medical_id', 'medicines.name', 'medicines.formulation_strength')
            ->orderByDesc('total_issued')
            ->limit(5)
            ->get();

        $distributionTrend = DB::table('distributions')
            ->join('medicines', 'distributions.medicine_id', '=', 'medicines.id')
            ->when($startDate, fn ($query) => $query->whereDate('distributions.transaction_date', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('distributions.transaction_date', '<=', $endDate))
            ->selectRaw('DATE(distributions.transaction_date) as day, SUM(distributions.quantity_issued) as total_issued')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(function ($item) {
                $item->day = \App\Helpers\DateHelper::formatDate($item->day);
                return $item;
            });

        $requestTrend = DB::table('requests')
            ->join('medicines', 'requests.medicine_id', '=', 'medicines.id')
            ->when($startDate, fn ($query) => $query->whereDate('requests.created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('requests.created_at', '<=', $endDate))
            ->selectRaw('DATE(requests.created_at) as day, SUM(requests.requested_quantity) as total_requested')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(function ($item) {
                $item->day = \App\Helpers\DateHelper::formatDate($item->day);
                return $item;
            });

        return compact(
            'summary',
            'topRequested',
            'topDistributed',
            'startDate',
            'endDate',
            'distributionTrend',
            'requestTrend'
        );
    }
}
