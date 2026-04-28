@extends('layouts.layout')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'A quick snapshot of inventory risk, stock value, and expiry exposure.')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('procurement.reports') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select id="category" name="category" class="form-select">
                        <option value="">All categories</option>
                        @foreach($categories as $categoryOption)
                            <option value="{{ $categoryOption }}" @selected($category === $categoryOption)>{{ $categoryOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Apply filters</button>
                </div>
                <div class="col-md-3">
                    <a
                        href="{{ route('procurement.reports.export', ['start_date' => $startDate, 'end_date' => $endDate, 'category' => $category]) }}"
                        class="btn btn-secondary w-100"
                    >
                        Export CSV
                    </a>
                </div>
                <div class="col-md-3">
                    <a
                        href="{{ route('procurement.reports.print', ['start_date' => $startDate, 'end_date' => $endDate, 'category' => $category]) }}"
                        target="_blank"
                        class="btn btn-info w-100"
                    >
                        Print / Save PDF
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Total medicines</div>
                <div class="fs-1 fw-bold">{{ $summary['totalMedicines'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Inventory value</div>
                <div class="fs-1 fw-bold">TZS {{ number_format($summary['inventoryValue'], 0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Risk profile</div>
                <div class="fs-1 fw-bold">{{ $summary['expiredCount'] + $summary['expiringSoonCount'] }}</div>
                <div class="text-secondary">expired or expiring soon</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Inventory risk summary</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-4" style="background: rgba(251,113,133,.08);">
                            <div class="text-secondary small">Expired medicines</div>
                            <div class="fs-3 fw-bold">{{ $summary['expiredCount'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-4" style="background: rgba(251,191,36,.08);">
                            <div class="text-secondary small">Expiring soon</div>
                            <div class="fs-3 fw-bold">{{ $summary['expiringSoonCount'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                            <div class="text-secondary small">Low stock medicines</div>
                            <div class="fs-3 fw-bold">{{ $summary['lowStockCount'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-4" style="background: rgba(110,231,183,.08);">
                            <div class="text-secondary small">Healthy stock lines</div>
                            <div class="fs-3 fw-bold">{{ $summary['totalMedicines'] - $summary['lowStockCount'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Demand overview</h5>
            </div>
            <div class="card-body">
                <div class="vstack gap-3">
                    @forelse($topDistributed as $medicine)
                        <div class="p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                            <div class="fw-semibold">{{ $medicine->name }}</div>
                            <div class="text-secondary small">{{ $medicine->category }} | {{ $medicine->total_issued }} issued</div>
                        </div>
                    @empty
                        <div class="p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                            No distribution data yet for demand analysis.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Request trend</h5>
            </div>
            <div class="card-body">
                <canvas id="requestTrendChart" height="180"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Distribution trend</h5>
            </div>
            <div class="card-body">
                <canvas id="distributionTrendChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Top requested medicines</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Category</th>
                            <th>Total requested</th>
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
                                <td colspan="3" class="text-center text-secondary py-4">No request data found for the selected filters.</td>
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
                <h5 class="card-title">Top distributed medicines</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Category</th>
                            <th>Total issued</th>
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
                                <td colspan="3" class="text-center text-secondary py-4">No distribution data found for the selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const requestTrendLabels = @json($requestTrend->pluck('day'));
    const requestTrendValues = @json($requestTrend->pluck('total_requested'));
    const distributionTrendLabels = @json($distributionTrend->pluck('day'));
    const distributionTrendValues = @json($distributionTrend->pluck('total_issued'));

    new Chart(document.getElementById('requestTrendChart'), {
        type: 'line',
        data: {
            labels: requestTrendLabels,
            datasets: [{
                label: 'Requested quantity',
                data: requestTrendValues,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.12)',
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    new Chart(document.getElementById('distributionTrendChart'), {
        type: 'bar',
        data: {
            labels: distributionTrendLabels,
            datasets: [{
                label: 'Issued quantity',
                data: distributionTrendValues,
                backgroundColor: 'rgba(14, 165, 168, 0.7)',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endsection
