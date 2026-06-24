<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            background: #ffffff;
        }

        .page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 28px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 22px;
            padding-bottom: 18px;
            border-bottom: 1px solid #cbd5e1;
        }

        .header h1 {
            margin: 0 0 6px;
            font-size: 24px;
        }

        .header p {
            margin: 0;
            color: #475569;
        }

        .meta {
            text-align: right;
            color: #475569;
            font-size: 13px;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #dbeafe;
            border-radius: 14px;
            padding: 14px;
        }

        .card .label {
            color: #64748b;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .card .value {
            font-size: 22px;
            font-weight: 700;
        }

        h2 {
            font-size: 18px;
            margin: 24px 0 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        th, td {
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            padding: 10px 8px;
            font-size: 13px;
        }

        th {
            color: #334155;
        }

        .muted {
            color: #64748b;
        }

        .status-approved { color: #15803d; font-weight: 700; }
        .status-rejected { color: #b91c1c; font-weight: 700; }
        .status-pending { color: #b45309; font-weight: 700; }

        .print-actions {
            margin-bottom: 20px;
        }

        .print-btn {
            border: 0;
            background: #2563eb;
            color: white;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 700;
            cursor: pointer;
        }

        @media print {
            .print-actions {
                display: none;
            }
            .page {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="print-actions">
            <button class="print-btn" onclick="window.print()">Print / Save as PDF</button>
        </div>

        <div class="header">
            <div>
                <h1>Pharmacist Report</h1>
                <p>My request history, approved stock, and inventory readiness.</p>
            </div>
            <div class="meta">
                <div><strong>Generated:</strong> {{ now()->format('d/m/Y H:i') }}</div>
                <div><strong>Period:</strong> {{ $startDate ?: 'All' }} - {{ $endDate ?: 'All' }}</div>
            </div>
        </div>

        <div class="summary">
            <div class="card"><div class="label">Total requests</div><div class="value">{{ $summary['totalRequests'] }}</div></div>
            <div class="card"><div class="label">Approved requests</div><div class="value">{{ $summary['approvedRequests'] }}</div></div>
            <div class="card"><div class="label">Rejected requests</div><div class="value">{{ $summary['rejectedRequests'] }}</div></div>
            <div class="card"><div class="label">Pending requests</div><div class="value">{{ $summary['pendingRequests'] }}</div></div>
        </div>

        <div class="summary">
            <div class="card"><div class="label">Requested units</div><div class="value">{{ $summary['requestedUnits'] }}</div></div>
            <div class="card"><div class="label">Approved units</div><div class="value">{{ $summary['approvedUnits'] }}</div></div>
            <div class="card"><div class="label">Pharmacy stock units</div><div class="value">{{ $summary['pharmacyStockUnits'] }}</div></div>
            <div class="card"><div class="label">Low stock lines</div><div class="value">{{ $summary['lowStockLines'] }}</div></div>
        </div>

        <h2>Approved Medicines</h2>
        <table>
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th>Approved Qty</th>
                    <th>Last Approved</th>
                </tr>
            </thead>
            <tbody>
                @forelse($approvedMedicines as $medicine)
                    <tr>
                        <td>{{ $medicine->medical_id }} - {{ $medicine->name }}</td>
                        <td>{{ $medicine->batch_number }}</td>
                        <td>{{ $medicine->total_approved }}</td>
                        <td>{{ \App\Helpers\DateHelper::formatDate($medicine->last_approved_at) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="muted">No approved medicines found for this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2>Top Requested Medicines</h2>
        <table>
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Total Requested</th>
                    <th>Total Approved</th>
                    <th>Total Rejected</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topRequestedMedicines as $medicine)
                    <tr>
                        <td>{{ $medicine->medical_id }} - {{ $medicine->name }}</td>
                        <td>{{ $medicine->total_requested }}</td>
                        <td>{{ $medicine->total_approved }}</td>
                        <td>{{ $medicine->total_rejected }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="muted">No request data found for this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2>Recent Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRequests as $request)
                    <tr>
                        <td>{{ $request->medicine->medical_id }} - {{ $request->medicine->name }}</td>
                        <td>{{ $request->requested_quantity }}</td>
                        <td class="status-{{ $request->status }}">{{ ucfirst($request->status) }}</td>
                        <td>{{ \App\Helpers\DateHelper::formatDate($request->created_at) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="muted">No recent requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
