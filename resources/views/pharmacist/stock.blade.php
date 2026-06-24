@extends('layouts.layout')

@section('title', 'My Pharmacy Stock')
@section('page-title', 'My Pharmacy Stock')
@section('page-subtitle', 'Medicines that have already been approved and transferred to your pharmacy inventory.')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Approved medicine lines</div>
                <div class="fs-2 fw-bold">{{ $approvedMedicines->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Total approved units</div>
                <div class="fs-2 fw-bold">{{ $approvedMedicines->sum('requested_quantity') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-secondary small">Low stock lines</div>
                <div class="fs-2 fw-bold">{{ $approvedMedicines->where('requested_quantity', '<=', 30)->count() }}</div>
            </div>
        </div>
    </div>
</div>

    <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-1">Approved pharmacy stock</h5>
            <div class="text-secondary small">This view shows medicines already approved and transferred by procurement.</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Medical ID</th>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th class="text-end">Approved Qty</th>
                    <th>Date Approved</th>
                </tr>
            </thead>
            <tbody>
                @forelse($approvedMedicines as $request)
                    <tr>
                        <td>{{ $request->medicine->medical_id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $request->medicine->name }}</div>
                            <small class="text-secondary">{{ $request->medicine->formulation_strength }}</small>
                        </td>
                        <td>{{ $request->medicine->batch_number }}</td>
                        <td class="text-end fw-bold text-success">{{ $request->requested_quantity }}</td>
                        <td>{{ $request->approved_at ? \App\Helpers\DateHelper::formatDate($request->approved_at) : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">No approved medicines have been received yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
