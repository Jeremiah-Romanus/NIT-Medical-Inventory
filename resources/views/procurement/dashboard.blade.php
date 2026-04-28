@extends('layouts.layout')

@section('title', 'Procurement Dashboard')
@section('page-title', 'Procurement Control Room')
@section('page-subtitle', 'Track stock, approvals, distributions, and report coverage in one view.')

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
                            You are in charge of approvals, stock replenishment, and distribution control.
                            Use the quick links to move fast while staying on top of medicine availability.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('procurement.stock') }}" class="btn btn-primary">
                                <i class="fa-solid fa-boxes-stacked me-2"></i>Manage Stock
                            </a>
                            <a href="{{ route('procurement.requests') }}" class="btn btn-info">
                                <i class="fa-solid fa-circle-check me-2"></i>Review Requests
                            </a>
                            <a href="{{ route('procurement.reports') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-chart-column me-2"></i>Open Reports
                            </a>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="display-6 fw-bold">TZS {{ number_format($stats['inventoryValue'], 0) }}</div>
                        <div class="text-secondary">Estimated inventory value</div>
                        <div class="mt-3 small text-warning">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            {{ $stats['expiredCount'] }} expired, {{ $stats['expiringSoonCount'] }} expiring soon
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">Operational snapshot</h5>
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
                        <div class="text-secondary small">Expired items</div>
                        <div class="fs-2 fw-bold">{{ $stats['expiredCount'] }}</div>
                    </div>
                    <i class="fa-solid fa-circle-xmark fs-1 text-danger opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small">Expiring soon</div>
                        <div class="fs-2 fw-bold">{{ $stats['expiringSoonCount'] }}</div>
                    </div>
                    <i class="fa-solid fa-hourglass-half fs-1 text-primary opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Recent requests</h5>
                <a href="{{ route('procurement.requests') }}" class="btn btn-sm btn-outline-light">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Requester</th>
                            <th>Medicine</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $request)
                            <tr>
                                <td>{{ $request->requester }}</td>
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
                                <td colspan="5" class="text-center text-secondary py-4">No requests yet.</td>
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
                <h5 class="card-title">Recent stock changes</h5>
            </div>
            <div class="card-body">
                <div class="vstack gap-3">
                    @forelse($recentMedicines as $medicine)
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-4" style="background: rgba(255,255,255,.04);">
                            <div>
                                <div class="fw-semibold">{{ $medicine->name }}</div>
                                <div class="text-secondary small">{{ $medicine->category }}</div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ $medicine->quantity }} units</div>
                                <div class="text-secondary small">TZS {{ number_format($medicine->unit_price, 2) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-secondary">No stock records yet.</div>
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
                <h5 class="card-title">High-demand medicines</h5>
            </div>
            <div class="card-body">
                <div class="vstack gap-3">
                    @forelse($topDemandMedicines as $medicine)
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                            <div class="fw-semibold">{{ $medicine->name }}</div>
                            <div class="small fw-bold">{{ $medicine->issued_total }} issued</div>
                        </div>
                    @empty
                        <div class="text-secondary">No distribution data yet for demand analysis.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Critical alerts</h5>
            </div>
            <div class="card-body">
                <div class="vstack gap-3">
                    @forelse($criticalAlerts as $medicine)
                        <div class="p-3 rounded-4" style="background: rgba(251,191,36,.08);">
                            <div class="fw-semibold">{{ $medicine->name }}</div>
                            <div class="text-secondary small">
                                Stock: {{ $medicine->quantity }} |
                                Expiry: {{ $medicine->expiry_date->format('M d, Y') }}
                            </div>
                        </div>
                    @empty
                        <div class="text-secondary">No critical stock or expiry alerts right now.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
