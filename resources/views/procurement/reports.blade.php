@extends('layouts.layout')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'A quick snapshot of inventory risk, stock value, and expiry exposure.')

@section('content')
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
                <h5 class="card-title">Next actions</h5>
            </div>
            <div class="card-body">
                <div class="vstack gap-3">
                    <div class="p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                        Use these metrics to prioritize reordering and disposal.
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(251,191,36,.08);">
                        Add PDF or Excel export next once the frontend screens are locked.
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(110,231,183,.08);">
                        Link these summary cards to charts in the next sprint.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
