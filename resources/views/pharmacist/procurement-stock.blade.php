@extends('layouts.layout')

@section('title', 'Procurement Stock')
@section('page-title', 'Procurement Stock')
@section('page-subtitle', 'Medicines currently available in procurement for planning new requests.')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Available medicine lines</div>
                <div class="fs-2 fw-bold">{{ $medicines->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Total units available</div>
                <div class="fs-2 fw-bold">{{ $medicines->sum('quantity') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Ready to request</div>
                <div class="fs-2 fw-bold">{{ $medicines->where('quantity', '>', 0)->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-1">Procurement inventory</h5>
            <div class="text-secondary small">Use this stock view before submitting a request.</div>
        </div>
        <a href="{{ route('pharmacist.request') }}" class="btn btn-sm btn-outline-primary">
            <i class="fa-solid fa-clipboard-list me-1"></i>Make request
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Medical ID</th>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th class="text-end">Procurement Qty</th>
                    <th class="text-end">Pharmacy Qty</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $medicine)
                    <tr>
                        <td>{{ $medicine->medical_id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $medicine->name }}</div>
                            <small class="text-secondary">{{ $medicine->formulation_strength }}</small>
                        </td>
                        <td>{{ $medicine->batch_number }}</td>
                        <td class="text-end fw-bold text-primary">{{ $medicine->quantity }}</td>
                        <td class="text-end">{{ $medicine->pharmacy_quantity }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">No procurement stock is currently available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
