@extends('layouts.layout')

@section('title', 'Procurement Dashboard')
@section('page-title', 'Procurement Dashboard')
@section('page-subtitle', 'Open each module on its own page for stock control, approvals, distribution, and reporting.')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start">
                    <div>
                        <div class="badge bg-primary-subtle text-primary mb-3 px-3 py-2">
                            Procurement officer workspace
                        </div>
                        <h3 class="fw-bold mb-3">Welcome back, {{ auth()->user()->name }}</h3>
                        <p class="text-secondary mb-4" style="max-width: 58ch;">
                            This dashboard is now a clean starting point. Use the module cards below to open
                            inventory, approvals, distribution, and reports on their own separate pages.
                        </p>
                    </div>

                    <div class="text-end">
                        <div class="display-6 fw-bold">TZS {{ number_format($stats['inventoryValue'], 0) }}</div>
                        <div class="text-secondary">Estimated inventory value</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">Quick health check</h5>
                <div class="d-grid gap-3">
                    <div class="p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                        <div class="text-secondary small">Pending requests</div>
                        <div class="fs-3 fw-bold">{{ $stats['pendingRequests'] }}</div>
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(110,231,183,.08);">
                        <div class="text-secondary small">Today's distributions</div>
                        <div class="fs-3 fw-bold">{{ $stats['todayDistributions'] }}</div>
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(251,191,36,.08);">
                        <div class="text-secondary small">Low stock medicines</div>
                        <div class="fs-3 fw-bold">{{ $stats['lowStockCount'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('procurement.stock') }}" class="text-reset d-block h-100">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-secondary small">Inventory</div>
                            <div class="fs-4 fw-bold">Manage Stock</div>
                        </div>
                        <i class="fa-solid fa-boxes-stacked fs-2 text-info opacity-75"></i>
                    </div>
                    <p class="text-secondary mb-0">Open the full inventory page to add, review, edit, and inspect medicines.</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="{{ route('procurement.requests') }}" class="text-reset d-block h-100">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-secondary small">Approvals</div>
                            <div class="fs-4 fw-bold">Review Requests</div>
                        </div>
                        <i class="fa-solid fa-circle-check fs-2 text-warning opacity-75"></i>
                    </div>
                    <p class="text-secondary mb-0">Handle all pharmacist requests on a dedicated approvals page.</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="{{ route('procurement.distribution') }}" class="text-reset d-block h-100">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-secondary small">Distribution</div>
                            <div class="fs-4 fw-bold">Issue Medicines</div>
                        </div>
                        <i class="fa-solid fa-truck fs-2 text-primary opacity-75"></i>
                    </div>
                    <p class="text-secondary mb-0">Record medicine distribution and review distribution history separately.</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="{{ route('procurement.reports') }}" class="text-reset d-block h-100">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-secondary small">Reports</div>
                            <div class="fs-4 fw-bold">Analytics & Export</div>
                        </div>
                        <i class="fa-solid fa-chart-column fs-2 text-success opacity-75"></i>
                    </div>
                    <p class="text-secondary mb-0">Open the reporting page for trends, summaries, CSV export, and print view.</p>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Total medicines</div>
                <div class="fs-2 fw-bold">{{ $stats['totalMedicines'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Pending requests</div>
                <div class="fs-2 fw-bold">{{ $stats['pendingRequests'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Expired items</div>
                <div class="fs-2 fw-bold">{{ $stats['expiredCount'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Expiring soon</div>
                <div class="fs-2 fw-bold">{{ $stats['expiringSoonCount'] }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
