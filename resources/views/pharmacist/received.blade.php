@extends('layouts.layout')

@section('title', 'Received Medicines')
@section('page-title', 'Received from Procurement')
@section('page-subtitle', 'Medicines that have been approved and issued to you by procurement.')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Received medicines</h5>
        <a href="{{ route('pharmacist.stock') }}" class="btn btn-sm btn-outline-secondary">View Pharmacy Inventory</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th>Approved Qty</th>
                    <th>Procurement Reduced</th>
                    <th>Date Approved</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receivedRequests as $req)
                    <tr>
                        <td>
                            <a href="{{ route('medicines.show', $req->medicine->id) }}">
                                {{ $req->medicine->medical_id }} - {{ $req->medicine->name }}
                            </a>
                        </td>
                        <td>{{ $req->medicine->batch_number }}</td>
                        <td>{{ $req->requested_quantity }}</td>
                        <td>{{ $req->requested_quantity }}</td>
                        <td>{{ \App\Helpers\DateHelper::formatDate($req->updated_at) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">No approved (received) medicines yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
