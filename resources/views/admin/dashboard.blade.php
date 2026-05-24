@extends('layouts.layout')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Manage users, monitor stock activity, and supervise the whole medical inventory workflow.')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">System users</div>
                    <div class="display-6 fw-bold">{{ $stats['totalUsers'] }}</div>
                    <div class="small text-muted mt-2">
                        {{ $stats['pharmacists'] }} pharmacists, {{ $stats['procurementOfficers'] }} procurement officers
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Medicines in system</div>
                    <div class="display-6 fw-bold">{{ $stats['totalMedicines'] }}</div>
                    <div class="small text-muted mt-2">All medicine records currently available</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Pending requests</div>
                    <div class="display-6 fw-bold">{{ $stats['pendingRequests'] }}</div>
                    <div class="small text-muted mt-2">Requests waiting for review</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Inventory value</div>
                    <div class="display-6 fw-bold">TZS {{ number_format($stats['inventoryValue'], 2) }}</div>
                    <div class="small text-muted mt-2">{{ $stats['totalDistributions'] }} distributions recorded</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Quick health view</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span>Expired medicines</span>
                        <strong>{{ $stats['expiredCount'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span>Expiring within 6 months</span>
                        <strong>{{ $stats['expiringSoonCount'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span>Active system roles</span>
                        <strong>Admin, Pharmacist, Procurement</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Recent users</h5>
                    <a href="{{ route('admin.users') }}" class="btn btn-primary btn-sm">Manage users</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->role === 'procurement' ? 'Procurement Officer' : ucfirst($user->role) }}</td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Recent medicine requests</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Requester</th>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $request)
                            <tr>
                                <td>{{ $request->user?->name }}</td>
                                <td>{{ $request->medicine?->name }}</td>
                                <td>{{ $request->requested_quantity }}</td>
                                <td>{{ ucfirst($request->status) }}</td>
                                <td>{{ $request->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No requests recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
