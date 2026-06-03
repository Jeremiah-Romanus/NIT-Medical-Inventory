@extends('layouts.layout')

@section('title', __('nav.dashboard') . ' — ' . __('role.procurement'))
@section('page-title', __('role.procurement') . ' ' . __('nav.dashboard'))
@section('page-subtitle', __('dashboard.system_alerts'))

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start">
                    <div>
                        <div class="badge bg-primary-subtle text-primary mb-3 px-3 py-2">
                            {{ __('role.procurement') }}
                        </div>
                        <h3 class="fw-bold mb-3">{{ __('dashboard.welcome', ['name' => auth()->user()->name]) }}</h3>
                        <p class="text-secondary mb-4" style="max-width: 58ch;">
                            {{ __('nav.role_badge') }}
                        </p>
                    </div>

                    <div class="text-end">
                        <div class="display-6 fw-bold">{{ __('currency.tzs') }} {{ number_format($stats['inventoryValue'], 0) }}</div>
                        <div class="text-secondary">{{ __('dashboard.inventory_value') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">{{ __('dashboard.health_check') }}</h5>
                <div class="d-grid gap-3">
                    <div class="p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                        <div class="text-secondary small">{{ __('dashboard.pending_requests') }}</div>
                        <div class="fs-3 fw-bold">{{ $stats['pendingRequests'] }}</div>
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(110,231,183,.08);">
                        <div class="text-secondary small">{{ __('dashboard.today_distributions') }}</div>
                        <div class="fs-3 fw-bold">{{ $stats['todayDistributions'] }}</div>
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(251,191,36,.08);">
                        <div class="text-secondary small">{{ __('dashboard.low_stock') }}</div>
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
                            <div class="text-secondary small">{{ __('nav.inventory') }}</div>
                            <div class="fs-4 fw-bold">{{ __('medicine.title') }}</div>
                        </div>
                        <i class="fa-solid fa-boxes-stacked fs-2 text-info opacity-75"></i>
                    </div>
                    <p class="text-secondary mb-0">{{ __('nav.inventory') }}</p>
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
                            <div class="text-secondary small">{{ __('nav.approvals') }}</div>
                            <div class="fs-4 fw-bold">{{ __('request.pending') }}</div>
                        </div>
                        <i class="fa-solid fa-circle-check fs-2 text-warning opacity-75"></i>
                    </div>
                    <p class="text-secondary mb-0">{{ __('nav.approve') }}</p>
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
                            <div class="text-secondary small">{{ __('nav.distribution') }}</div>
                            <div class="fs-4 fw-bold">{{ __('distribution.record') }}</div>
                        </div>
                        <i class="fa-solid fa-truck fs-2 text-primary opacity-75"></i>
                    </div>
                    <p class="text-secondary mb-0">{{ __('nav.distribution') }}</p>
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
                            <div class="text-secondary small">{{ __('nav.reports') }}</div>
                            <div class="fs-4 fw-bold">{{ __('report.summary') }}</div>
                        </div>
                        <i class="fa-solid fa-chart-column fs-2 text-success opacity-75"></i>
                    </div>
                    <p class="text-secondary mb-0">{{ __('nav.reports') }}</p>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">{{ __('dashboard.recent_requests') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('request.requester') }}</th>
                                <th>{{ __('request.medicine') }}</th>
                                <th>{{ __('request.quantity') }}</th>
                                <th>{{ __('medicine.status') }}</th>
                                <th>{{ __('common.created') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRequests as $req)
                                <tr>
                                    <td>{{ $req->requester }}</td>
                                    <td>{{ $req->medicine }}</td>
                                    <td>{{ $req->quantity }}</td>
                                    <td>
                                        <span class="badge @if($req->status === 'pending') bg-warning @elseif($req->status === 'approved') bg-success @else bg-danger @endif">
                                            {{ __($req->status === 'pending' ? 'request.status.pending' : ($req->status === 'approved' ? 'request.status.approved' : 'request.status.rejected')) }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($req->created_at)->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">{{ __('request.no_requests') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">{{ __('dashboard.top_demand') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('medicine.name') }}</th>
                                <th class="text-end">{{ __('request.quantity') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topDemandMedicines as $demand)
                                <tr>
                                    <td>{{ $demand->name }}</td>
                                    <td class="text-end fw-bold">{{ $demand->issued_total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-3 text-muted">{{ __('distribution.no_distributions') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">{{ __('dashboard.total_medicines') }}</div>
                <div class="fs-2 fw-bold">{{ $stats['totalMedicines'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">{{ __('dashboard.pending_requests') }}</div>
                <div class="fs-2 fw-bold">{{ $stats['pendingRequests'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">{{ __('dashboard.expired') }}</div>
                <div class="fs-2 fw-bold">{{ $stats['expiredCount'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">{{ __('dashboard.expiring_soon') }}</div>
                <div class="fs-2 fw-bold">{{ $stats['expiringSoonCount'] }}</div>
            </div>
        </div>
    </div>
</div>

@if(isset($criticalAlerts) && $criticalAlerts->isNotEmpty())
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title"><i class="fa-solid fa-bell me-2"></i>{{ __('dashboard.critical_alerts') }}</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>{{ __('medicine.name') }}</th>
                        <th>{{ __('medicine.quantity') }}</th>
                        <th>{{ __('medicine.expiry_date') }}</th>
                        <th>{{ __('medicine.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criticalAlerts as $alert)
                        <tr>
                            <td>{{ $alert->name }}</td>
                            <td>
                                <span class="badge @if($alert->quantity < 50) bg-danger @else bg-warning @endif">
                                    {{ $alert->quantity }}
                                </span>
                            </td>
                            <td>{{ \App\Helpers\DateHelper::formatDate($alert->expiry_date) }}</td>
                            <td>
                                @if($alert->isExpired())
                                    <span class="badge bg-danger">{{ __('medicine.expired') }}</span>
                                @elseif($alert->isExpiringSoon())
                                    <span class="badge bg-warning">{{ __('medicine.expiring_soon') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('medicine.low_stock') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection