@extends('layouts.layout')

@section('title', __('nav.dashboard') . ' — ' . __('role.admin'))
@section('page-title', __('role.admin') . ' ' . __('nav.dashboard'))
@section('page-subtitle', __('dashboard.system_alerts'))

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">{{ __('dashboard.total_users') }}</div>
                    <div class="display-6 fw-bold">{{ $stats['totalUsers'] }}</div>
                    <div class="small text-muted mt-2">
                        {{ $stats['pharmacists'] }} {{ __('role.pharmacist') }}, {{ $stats['procurementOfficers'] }} {{ __('role.procurement') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">{{ __('dashboard.total_medicines') }}</div>
                    <div class="display-6 fw-bold">{{ $stats['totalMedicines'] }}</div>
                    <div class="small text-muted mt-2">{{ __('medicine.total') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">{{ __('dashboard.pending_requests') }}</div>
                    <div class="display-6 fw-bold">{{ $stats['pendingRequests'] }}</div>
                    <div class="small text-muted mt-2">{{ __('request.pending') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">{{ __('dashboard.inventory_value_short') }}</div>
                    <div class="display-6 fw-bold">{{ __('currency.tzs') }} {{ number_format($stats['inventoryValue'], 2) }}</div>
                    <div class="small text-muted mt-2">
                        {{ $stats['totalDistributions'] }} {{ __('dashboard.total_distributions') }},
                        {{ $stats['auditLogs'] }} audit logs
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">{{ __('dashboard.health_check') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span>{{ __('dashboard.expired') }}</span>
                        <strong>{{ $stats['expiredCount'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span>{{ __('dashboard.expiring_soon') }}</span>
                        <strong>{{ $stats['expiringSoonCount'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span>{{ __('dashboard.total_users') }}</span>
                        <strong>{{ __('role.admin') }}, {{ __('role.pharmacist') }}, {{ __('role.procurement') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">{{ __('dashboard.recent_users') }}</h5>
                    <a href="{{ route('admin.users') }}" class="btn btn-primary btn-sm">{{ __('nav.users') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('user.name') }}</th>
                                    <th>{{ __('user.email') }}</th>
                                    <th>{{ __('user.phone') }}</th>
                                    <th>{{ __('user.role') }}</th>
                                    <th>{{ __('user.joined') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>
                                            @switch($user->role)
                                                @case('admin') {{ __('role.admin') }} @break
                                                @case('procurement') {{ __('role.procurement') }} @break
                                                @default {{ __('role.pharmacist') }}
                                            @endswitch
                                        </td>
                                        <td>{{ \App\Helpers\DateHelper::formatDate($user->created_at) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">{{ __('user.no_users') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Recent audit activity</h5>
            <a href="{{ route('admin.audit-trail') }}" class="btn btn-primary btn-sm">View audit trail</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Record</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAuditLogs as $log)
                            <tr>
                                <td>{{ \App\Helpers\DateHelper::formatDateTime($log->created_at) }}</td>
                                <td>{{ $log->user?->name ?? 'System' }}</td>
                                <td>{{ str_replace(['.', '_'], [' ', ' '], $log->action) }}</td>
                                <td>{{ $log->subject ?? 'Not specified' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No audit records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ __('request.title') }}</h5>
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
                            <th>{{ __('request.title') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $request)
                            <tr>
                                <td>{{ $request->user?->name }}</td>
                                <td>{{ $request->medicine?->name }}</td>
                                <td>{{ $request->requested_quantity }}</td>
                                <td>{{ __($request->status === 'pending' ? 'request.status.pending' : ($request->status === 'approved' ? 'request.status.approved' : 'request.status.rejected')) }}</td>
                                <td>{{ \App\Helpers\DateHelper::formatDateTime($request->created_at) }}</td>
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
@endsection
