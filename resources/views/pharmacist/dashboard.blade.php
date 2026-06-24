@extends('layouts.layout')

@section('title', 'Pharmacist Dashboard')
@section('page-title', 'Pharmacist Dashboard')
@section('page-subtitle', 'Review your stock, monitor procurement availability, and follow request outcomes in one place.')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-4 p-md-5">
                <div class="badge bg-info-subtle text-info mb-3 px-3 py-2">
                    Pharmacist workspace
                </div>
                <h3 class="fw-bold mb-3">Welcome back, {{ auth()->user()->name }}</h3>
                <p class="text-secondary mb-4" style="max-width: 64ch;">
                    This dashboard gives you a clean split between what is already in your pharmacy stock and
                    what is still available in procurement so you can request with confidence.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('pharmacist.stock') }}" class="btn btn-primary">
                        <i class="fa-solid fa-boxes-stacked me-1"></i>Pharmacy Stock
                    </a>
                    <a href="{{ route('pharmacist.procurement-stock') }}" class="btn btn-outline-primary">
                        <i class="fa-solid fa-warehouse me-1"></i>Procurement Stock
                    </a>
                    <a href="{{ route('pharmacist.request') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-clipboard-list me-1"></i>Make Request
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
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">My pharmacy stock</h5>
                    <div class="text-secondary small">{{ $stats['pharmacyStockMedicines'] }} medicines | {{ $stats['pharmacyStockUnits'] }} units</div>
                </div>
                <a href="{{ route('pharmacist.stock') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-up-right-from-square me-1"></i>Open
                </a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th class="text-end">Qty in pharmacy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pharmacyStockPreview as $medicine)
                            <tr>
                                <td>{{ $medicine->name }}</td>
                                <td>{{ $medicine->batch_number }}</td>
                                <td class="text-end fw-bold">{{ $medicine->pharmacy_quantity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-4">No medicine has been received yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">Procurement stock</h5>
                    <div class="text-secondary small">{{ $stats['procurementStockMedicines'] }} medicines | {{ $stats['procurementStockUnits'] }} units</div>
                </div>
                <a href="{{ route('pharmacist.procurement-stock') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-up-right-from-square me-1"></i>Open
                </a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th class="text-end">Qty available</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($procurementStockPreview as $medicine)
                            <tr>
                                <td>{{ $medicine->name }}</td>
                                <td>{{ $medicine->batch_number }}</td>
                                <td class="text-end fw-bold">{{ $medicine->quantity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-4">No procurement stock is currently available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">Recent request notifications</h5>
                    <span class="text-secondary small">{{ $notifications->count() }} unread</span>
                </div>
                @if($notifications->isNotEmpty())
                    <form method="POST" action="{{ route('pharmacist.notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-check-double me-1"></i>Mark all read
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <div class="list-group-item border-0 py-3">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <div class="fw-semibold">
                                        {{ $notification->data['message'] ?? 'Request update received.' }}
                                    </div>
                                    <div class="text-secondary small mt-1">
                                        {{ $notification->data['medicine_name'] ?? 'Medicine' }} |
                                        {{ $notification->created_at?->format('d M Y, H:i') }}
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark align-self-start">
                                    {{ $notification->type === \App\Notifications\MedicineRequestApproved::class ? 'Approved' : 'Rejected' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-secondary text-center">
                            No unread notifications at the moment.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent requests</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th class="text-end">Qty</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $req)
                            <tr>
                                <td>{{ $req->medicine }}</td>
                                <td class="text-end">{{ $req->quantity }}</td>
                                <td>
                                    <span class="badge @if($req->status === 'pending') bg-warning text-dark @elseif($req->status === 'approved') bg-success @else bg-danger @endif">
                                        {{ ucfirst($req->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-4">No recent requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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

@if(isset($lowStockMedicines) && $lowStockMedicines->isNotEmpty())
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Low stock medicines</h5>
                    <p class="small text-secondary mb-0">Medicines with 30 units or fewer in the pharmacy store.</p>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Batch</th>
                                <th class="text-end">Pharmacy Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockMedicines as $medicine)
                                <tr>
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->batch_number }}</td>
                                    <td class="text-end fw-bold text-danger">{{ $medicine->pharmacy_quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
