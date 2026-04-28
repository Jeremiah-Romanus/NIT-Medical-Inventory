@extends('layouts.layout')

@section('title', 'Submit Request')
@section('page-title', 'Submit Medicine Request')
@section('page-subtitle', 'Create a request to procurement with the exact stock you need.')

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Request form</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('requests.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Medicine</label>
                        <select name="medicine_id" class="form-select @error('medicine_id') is-invalid @enderror" required>
                            <option selected disabled>Select medicine</option>
                            @forelse($medicines as $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->name }} - {{ $medicine->category }}</option>
                            @empty
                                <option disabled>No medicines available</option>
                            @endforelse
                        </select>
                        @error('medicine_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Requested quantity</label>
                            <input type="number" name="requested_quantity" min="1" class="form-control @error('requested_quantity') is-invalid @enderror" placeholder="Enter quantity needed" required>
                            @error('requested_quantity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Remarks</label>
                            <input type="text" name="remarks" class="form-control @error('remarks') is-invalid @enderror" placeholder="Add a short note if needed">
                            @error('remarks')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Send Request</button>
                        <a href="{{ route('pharmacist.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">Request tips</h5>
                <div class="vstack gap-3">
                    <div class="p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                        Select the exact medicine and quantity to speed up approval.
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(251,191,36,.08);">
                        Add justification when stock is low or patient demand is rising.
                    </div>
                    <div class="p-3 rounded-4" style="background: rgba(110,231,183,.08);">
                        Track your submitted requests below and follow approval status in real time.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">My requests</h5>
        <span class="text-secondary small">Latest requests first</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myRequests as $request)
                    <tr>
                        <td>{{ $request->medicine->name }}</td>
                        <td>{{ $request->requested_quantity }}</td>
                        <td>
                            <span class="badge
                                @if($request->status === 'approved') bg-success
                                @elseif($request->status === 'rejected') bg-danger
                                @else bg-warning text-dark
                                @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td>{{ $request->remarks ?: 'No remarks' }}</td>
                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">No requests submitted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
