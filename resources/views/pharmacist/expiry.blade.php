@extends('layouts.layout')

@section('title', 'Expiry Tracker')
@section('page-title', 'Expiry Tracker')
@section('page-subtitle', 'Stay ahead of expired and soon-to-expire medicines.')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Expired medicines</div>
                <div class="fs-1 fw-bold">{{ $medicines->filter(fn ($medicine) => $medicine->isExpired())->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Expiring soon</div>
                <div class="fs-1 fw-bold">{{ $medicines->filter(fn ($medicine) => $medicine->isExpiringSoon())->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Active stock lines</div>
                <div class="fs-1 fw-bold">{{ $medicines->filter(fn ($medicine) => ! $medicine->isExpired() && ! $medicine->isExpiringSoon())->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Expiry list</h5>
        <span class="text-secondary small">Sorted by expiry date</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th>Qty</th>
                    <th>Expiry date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $medicine)
                    <tr @class([
                        'table-danger' => $medicine->isExpired(),
                        'table-warning' => $medicine->isExpiringSoon() && ! $medicine->isExpired()
                    ])>
                        <td>{{ $medicine->name }}</td>
                        <td>{{ $medicine->batch_number }}</td>
                        <td>{{ $medicine->quantity }}</td>
                        <td>{{ $medicine->expiry_date->format('M d, Y') }}</td>
                        <td>
                            @if($medicine->isExpired())
                                <span class="badge bg-danger">Expired</span>
                            @elseif($medicine->isExpiringSoon())
                                <span class="badge bg-warning text-dark">Expiring soon</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">No medicines available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
