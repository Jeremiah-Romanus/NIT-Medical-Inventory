@extends('layouts.layout')

@section('title', 'Pharmacist Dashboard')
@section('page-title', 'Pharmacist Operations')
@section('page-subtitle', 'View stock health, track expiry risks, and keep requests moving.')

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
                    Use this dashboard to monitor available stock, surface expiry risks, and submit requests
                    that keep your pharmacy moving without delays.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('pharmacist.stock') }}" class="btn btn-primary">
                        <i class="fa-solid fa-boxes-stacked me-2"></i>View Inventory
                    </a>
                    <a href="{{ route('pharmacist.request') }}" class="btn btn-info">
                        <i class="fa-solid fa-clipboard-list me-2"></i>Submit Request
                    </a>
                    <a href="{{ route('pharmacist.expiry') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-calendar-days me-2"></i>Check Expiry
                    </a>
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
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small">Total medicines</div>
                        <div class="fs-2 fw-bold">{{ $stats['totalMedicines'] }}</div>
                    </div>
                    <i class="fa-solid fa-pills fs-1 text-info opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small">Pending requests</div>
                        <div class="fs-2 fw-bold">{{ $stats['pendingRequests'] }}</div>
                    </div>
                    <i class="fa-solid fa-inbox fs-1 text-warning opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small">Approved requests</div>
                        <div class="fs-2 fw-bold">{{ $stats['approvedRequests'] }}</div>
                    </div>
                    <i class="fa-solid fa-circle-check fs-1 text-success opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small">Rejected requests</div>
                        <div class="fs-2 fw-bold">{{ $stats['rejectedRequests'] }}</div>
                    </div>
                    <i class="fa-solid fa-circle-xmark fs-1 text-danger opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">My recent requests</h5>
                <a href="{{ route('pharmacist.request') }}" class="btn btn-sm btn-outline-light">New request</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $request)
                            <tr>
                                <td>{{ $request->medicine }}</td>
                                <td>{{ $request->quantity }}</td>
                                <td>
                                    <span class="badge
                                        @if($request->status === 'approved') bg-success
                                        @elseif($request->status === 'rejected') bg-danger
                                        @else bg-warning text-dark
                                        @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ \Illuminate\Support\Carbon::parse($request->created_at)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">No requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Expiry watchlist</h5>
            </div>
            <div class="card-body">
                <div class="vstack gap-3">
                    @forelse($expiringMedicines as $medicine)
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-4" style="background: rgba(255,255,255,.04);">
                            <div>
                                <div class="fw-semibold">{{ $medicine->name }}</div>
                                <div class="text-secondary small">{{ $medicine->expiry_date->format('M d, Y') }}</div>
                            </div>
                            <span class="badge bg-warning text-dark">Soon</span>
                        </div>
                    @empty
                        <div class="text-secondary">No expiring medicines right now.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
