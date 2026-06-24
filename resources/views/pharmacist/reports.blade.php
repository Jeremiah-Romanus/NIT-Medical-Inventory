@extends('layouts.layout')

@section('title', 'Pharmacist Reports')
@section('page-title', 'Pharmacist Reports')
@section('page-subtitle', 'Review your approved medicines, request outcomes, and stock readiness in one report.')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('pharmacist.reports') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ \App\Helpers\DateHelper::formatForInput($startDate) }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ \App\Helpers\DateHelper::formatForInput($endDate) }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Report scope</label>
                    <input type="text" class="form-control" value="My request history only" disabled>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Apply filters</button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('pharmacist.reports.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn-info w-100">
                        Print / Save PDF
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Total requests</div>
                <div class="fs-1 fw-bold">{{ $summary['totalRequests'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Approved requests</div>
                <div class="fs-1 fw-bold text-success">{{ $summary['approvedRequests'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Rejected requests</div>
                <div class="fs-1 fw-bold text-danger">{{ $summary['rejectedRequests'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Pending requests</div>
                <div class="fs-1 fw-bold text-warning">{{ $summary['pendingRequests'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Requested units</div>
                <div class="fs-2 fw-bold">{{ $summary['requestedUnits'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Approved units</div>
                <div class="fs-2 fw-bold text-success">{{ $summary['approvedUnits'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Pharmacy stock units</div>
                <div class="fs-2 fw-bold">{{ $summary['pharmacyStockUnits'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Low stock lines</div>
                <div class="fs-2 fw-bold text-danger">{{ $summary['lowStockLines'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Request trend</h5>
            </div>
            <div class="card-body">
                <canvas id="requestTrendChart" height="220"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Request status</h5>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="statusChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Top requested medicines</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Strength</th>
                            <th class="text-end">Requested</th>
                            <th class="text-end">Approved</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topRequestedMedicines as $medicine)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $medicine->medical_id }} - {{ $medicine->name }}</div>
                                </td>
                                <td>{{ $medicine->formulation_strength }}</td>
                                <td class="text-end">{{ $medicine->total_requested }}</td>
                                <td class="text-end text-success">{{ $medicine->total_approved }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">No request data found for the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Approved medicines</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th class="text-end">Approved qty</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvedMedicines as $medicine)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $medicine->medical_id }} - {{ $medicine->name }}</div>
                                    <small class="text-secondary">{{ $medicine->formulation_strength }}</small>
                                </td>
                                <td>{{ $medicine->batch_number }}</td>
                                <td class="text-end text-success fw-bold">{{ $medicine->total_approved }}</td>
                                <td>{{ \App\Helpers\DateHelper::formatDate($medicine->last_approved_at) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">No approved medicines yet for the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Recent request history</h5>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th class="text-end">Quantity</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRequests as $request)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $request->medicine->medical_id }} - {{ $request->medicine->name }}</div>
                            <small class="text-secondary">{{ $request->medicine->formulation_strength }}</small>
                        </td>
                        <td class="text-end">{{ $request->requested_quantity }}</td>
                        <td>
                            <span class="badge
                                @if($request->status === 'approved') bg-success
                                @elseif($request->status === 'rejected') bg-danger
                                @else bg-warning text-dark
                                @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td>{{ \App\Helpers\DateHelper::formatDate($request->created_at) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">No recent requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const trendLabels = @json($requestTrend->pluck('day'));
    const requestTotals = @json($requestTrend->pluck('total_requests'));
    const approvedTotals = @json($requestTrend->pluck('approved_count'));
    const rejectedTotals = @json($requestTrend->pluck('rejected_count'));

    new Chart(document.getElementById('requestTrendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [
                {
                    label: 'Requests',
                    data: requestTotals,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    tension: 0.35,
                    fill: true
                },
                {
                    label: 'Approved',
                    data: approvedTotals,
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.12)',
                    tension: 0.35,
                    fill: false
                },
                {
                    label: 'Rejected',
                    data: rejectedTotals,
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.12)',
                    tension: 0.35,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Rejected', 'Pending'],
            datasets: [{
                data: [{{ $summary['approvedRequests'] }}, {{ $summary['rejectedRequests'] }}, {{ $summary['pendingRequests'] }}],
                backgroundColor: ['#16a34a', '#dc2626', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
