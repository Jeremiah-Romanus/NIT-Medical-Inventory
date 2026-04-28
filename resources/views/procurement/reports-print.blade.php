<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printable Report - NIT Medical Inventory</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #16233a;
            margin: 0;
            background: #f5f7fb;
        }

        .page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 24px 48px;
            background: #ffffff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 28px;
            border-bottom: 2px solid #d9e2ee;
            padding-bottom: 18px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 1.8rem;
        }

        .header p {
            margin: 4px 0;
            color: #475569;
        }

        .print-actions {
            display: flex;
            gap: 10px;
        }

        .print-btn {
            padding: 10px 14px;
            border-radius: 10px;
            border: 0;
            background: #0f2747;
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
        }

        .section {
            margin-top: 28px;
        }

        .section h2 {
            font-size: 1.1rem;
            margin: 0 0 14px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 12px;
        }

        .summary-card {
            border: 1px solid #d9e2ee;
            border-radius: 14px;
            padding: 14px;
            background: #f8fbff;
        }

        .summary-card .label {
            color: #64748b;
            font-size: 0.85rem;
        }

        .summary-card .value {
            font-size: 1.4rem;
            font-weight: 700;
            margin-top: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #d9e2ee;
            padding: 10px 12px;
            text-align: left;
            font-size: 0.92rem;
        }

        th {
            background: #eef4fb;
        }

        .trend-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .trend-box {
            border: 1px solid #d9e2ee;
            border-radius: 14px;
            padding: 16px;
            background: #f8fbff;
        }

        .trend-box ul {
            margin: 10px 0 0;
            padding-left: 18px;
        }

        .muted {
            color: #64748b;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .page {
                max-width: none;
                padding: 0;
            }

            .print-actions {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <h1>Medical Inventory and Distribution Report</h1>
                <p><strong>System:</strong> NIT Medical Inventory</p>
                <p><strong>Generated:</strong> {{ now()->format('M d, Y H:i') }}</p>
                <p><strong>Start Date:</strong> {{ $startDate ?: 'All' }}</p>
                <p><strong>End Date:</strong> {{ $endDate ?: 'All' }}</p>
                <p><strong>Category:</strong> {{ $category ?: 'All' }}</p>
            </div>
            <div class="print-actions">
                <button type="button" class="print-btn" onclick="window.print()">Print / Save as PDF</button>
            </div>
        </div>

        <div class="section">
            <h2>Summary</h2>
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="label">Total medicines</div>
                    <div class="value">{{ $summary['totalMedicines'] }}</div>
                </div>
                <div class="summary-card">
                    <div class="label">Expired</div>
                    <div class="value">{{ $summary['expiredCount'] }}</div>
                </div>
                <div class="summary-card">
                    <div class="label">Expiring soon</div>
                    <div class="value">{{ $summary['expiringSoonCount'] }}</div>
                </div>
                <div class="summary-card">
                    <div class="label">Low stock</div>
                    <div class="value">{{ $summary['lowStockCount'] }}</div>
                </div>
                <div class="summary-card">
                    <div class="label">Inventory value</div>
                    <div class="value">TZS {{ number_format($summary['inventoryValue'], 0) }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Usage Trends</h2>
            <div class="trend-grid">
                <div class="trend-box">
                    <strong>Request trend by day</strong>
                    @if($requestTrend->isEmpty())
                        <p class="muted">No request trend data available.</p>
                    @else
                        <ul>
                            @foreach($requestTrend as $row)
                                <li>{{ $row->day }}: {{ $row->total_requested }} requested</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="trend-box">
                    <strong>Distribution trend by day</strong>
                    @if($distributionTrend->isEmpty())
                        <p class="muted">No distribution trend data available.</p>
                    @else
                        <ul>
                            @foreach($distributionTrend as $row)
                                <li>{{ $row->day }}: {{ $row->total_issued }} issued</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Top Requested Medicines</h2>
            <table>
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Category</th>
                        <th>Total Requested</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topRequested as $medicine)
                        <tr>
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->category }}</td>
                            <td>{{ $medicine->total_requested }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="muted">No request data found for the selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>Top Distributed Medicines</h2>
            <table>
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Category</th>
                        <th>Total Issued</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topDistributed as $medicine)
                        <tr>
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->category }}</td>
                            <td>{{ $medicine->total_issued }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="muted">No distribution data found for the selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
