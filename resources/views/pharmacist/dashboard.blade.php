@extends('layouts.layout')

@section('title', 'Pharmacist Dashboard')
@section('page-title', 'Pharmacist Dashboard')
@section('page-subtitle', 'Use each module on its own page for inventory, requests, and expiry follow-up.')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-4 p-md-5">
                <div class="badge bg-info-subtle text-info mb-3 px-3 py-2">
                    Pharmacist workspace
                </div>
                <h3 class="fw-bold mb-3">Welcome back, {{ auth()->user()->name }}</h3>
                <p class="text-secondary mb-4" style="max-width: 58ch;">
                    This dashboard now stays focused on overview only. Open inventory, requests, and expiry
                    from the cards below so each task has its own separate page.
                </p>
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
                    <div class="p-3 rounded-4" style="background: rgba(251,191,36,.08);">
                        <div class="text-secondary small">Expiring soon</div>
                        <div class="fs-3 fw-bold">{{ $stats['expiringSoonCount'] }}</div>
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(251,113,133,.08);">
                        <div class="text-secondary small">Low stock medicines</div>
                        <div class="fs-3 fw-bold">{{ $stats['lowStockCount'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <a href="{{ route('pharmacist.stock') }}" class="text-reset d-block h-100">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-secondary small">Inventory</div>
                            <div class="fs-4 fw-bold">View Stock</div>
                        </div>
                        <i class="fa-solid fa-boxes-stacked fs-2 text-info opacity-75"></i>
                    </div>
                    <p class="text-secondary mb-0">Open the dedicated inventory page to review available medicines and stock status.</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('pharmacist.request') }}" class="text-reset d-block h-100">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-secondary small">Requests</div>
                            <div class="fs-4 fw-bold">Submit Requests</div>
                        </div>
                        <i class="fa-solid fa-clipboard-list fs-2 text-warning opacity-75"></i>
                    </div>
                    <p class="text-secondary mb-0">Use the request page to submit medicine requests and follow your request history.</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('pharmacist.expiry') }}" class="text-reset d-block h-100">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-secondary small">Expiry</div>
                            <div class="fs-4 fw-bold">Check Expiry</div>
                        </div>
                        <i class="fa-solid fa-calendar-days fs-2 text-primary opacity-75"></i>
                    </div>
                    <p class="text-secondary mb-0">Review expired and near-expiry medicines on their own page without dashboard clutter.</p>
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
                <div class="text-secondary small">Approved requests</div>
                <div class="fs-2 fw-bold">{{ $stats['approvedRequests'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Rejected requests</div>
                <div class="fs-2 fw-bold">{{ $stats['rejectedRequests'] }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
