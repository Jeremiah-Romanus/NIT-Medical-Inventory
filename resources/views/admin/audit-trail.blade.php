@extends('layouts.layout')

@section('title', 'Audit Trail')
@section('page-title', 'Audit Trail')
@section('page-subtitle', 'Review system activity, inventory changes, requests, and role updates.')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="card-title">System activity</h5>
                <form method="GET" action="{{ route('admin.audit-trail') }}" class="d-flex align-items-center gap-2 flex-wrap">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        class="form-control form-control-sm table-filter-input"
                        placeholder="Search user, action, or record"
                    >
                    <select name="action" class="form-select form-select-sm" style="min-width: 210px;">
                        <option value="">All actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" @selected(request('action') === $action)>
                                {{ str_replace(['.', '_'], [' ', ' '], $action) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-magnifying-glass me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['q', 'action']))
                        <a href="{{ route('admin.audit-trail') }}" class="btn btn-secondary btn-sm">Clear</a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Record</th>
                            <th>Changes</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-nowrap">{{ \App\Helpers\DateHelper::formatDateTime($log->created_at) }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $log->user?->name ?? 'System' }}</div>
                                    <div class="text-muted small">{{ $log->user?->email }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ str_replace(['.', '_'], [' ', ' '], $log->action) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $log->subject ?? 'Not specified' }}</div>
                                    @if($log->auditable_type)
                                        <div class="text-muted small">
                                            {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                                        </div>
                                    @endif
                                </td>
                                <td style="min-width: 280px;">
                                    @if($log->old_values || $log->new_values)
                                        <details>
                                            <summary class="text-primary fw-semibold">View details</summary>
                                            <div class="row g-2 mt-2">
                                                @if($log->old_values)
                                                    <div class="col-md-6">
                                                        <div class="text-muted small mb-1">Before</div>
                                                        <pre class="small bg-light border rounded p-2 mb-0">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                    </div>
                                                @endif
                                                @if($log->new_values)
                                                    <div class="col-md-6">
                                                        <div class="text-muted small mb-1">After</div>
                                                        <pre class="small bg-light border rounded p-2 mb-0">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                    </div>
                                                @endif
                                            </div>
                                        </details>
                                    @else
                                        <span class="text-muted">No value changes recorded</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $log->ip_address ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No audit records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
            <div class="card-footer bg-transparent">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection
