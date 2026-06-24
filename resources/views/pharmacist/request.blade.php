@extends('layouts.layout')

@section('title', 'Submit Request')
@section('page-title', 'Submit Medicine Request')
@section('page-subtitle', 'Create a request from medicines currently available in procurement stock.')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Request form</h5>
            </div>
            <div class="card-body p-4">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('requests.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-lg-7">
                            <label class="form-label">Medicine</label>
                            <select id="medicine-select" name="medicine_id" class="form-select @error('medicine_id') is-invalid @enderror" required>
                                <option selected disabled value="">Select medicine</option>
                                @forelse($medicines as $medicine)
                                    <option
                                        value="{{ $medicine->id }}"
                                        data-code="{{ $medicine->medical_id }}"
                                        data-name="{{ $medicine->name }}"
                                        data-strength="{{ $medicine->formulation_strength }}"
                                        data-stock="{{ $medicine->quantity }}"
                                        @selected(old('medicine_id') == $medicine->id)
                                    >
                                        {{ $medicine->medical_id }} - {{ $medicine->name }} ({{ $medicine->formulation_strength }}) | Available: {{ $medicine->quantity }}
                                    </option>
                                @empty
                                    <option disabled>No medicines available</option>
                                @endforelse
                            </select>
                            @error('medicine_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label class="form-label">Quantity requested</label>
                            <input
                                id="requested-quantity"
                                type="number"
                                name="requested_quantity"
                                min="1"
                                class="form-control @error('requested_quantity') is-invalid @enderror"
                                value="{{ old('requested_quantity') }}"
                                placeholder="Qty needed"
                                required
                            >
                            @error('requested_quantity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Remarks</label>
                            <input
                                type="text"
                                name="remarks"
                                class="form-control @error('remarks') is-invalid @enderror"
                                value="{{ old('remarks') }}"
                                placeholder="Add a short note if needed"
                            >
                            @error('remarks')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-paper-plane me-1"></i>Send Request
                        </button>
                        <a href="{{ route('pharmacist.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">My requests</h5>
        <span class="text-secondary small">Latest requests first</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myRequests as $request)
                    <tr>
                        <td>
                            <strong>{{ $request->medicine->medical_id }}</strong><br>
                            <small class="text-muted">{{ $request->medicine->name }}</small>
                        </td>
                        <td class="text-center">{{ $request->requested_quantity }}</td>
                        <td>
                            <span class="badge
                                @if($request->status === 'approved') bg-success
                                @elseif($request->status === 'rejected') bg-danger
                                @else bg-warning text-dark
                                @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                            @if($request->status === 'approved' && $request->approved_at)
                                <br>
                                <small class="text-success"><i class="fa-solid fa-check me-1"></i>{{ \App\Helpers\DateHelper::formatDate($request->approved_at) }}</small>
                            @elseif($request->status === 'rejected' && $request->rejected_at)
                                <br>
                                <small class="text-danger"><i class="fa-solid fa-xmark me-1"></i>{{ \App\Helpers\DateHelper::formatDate($request->rejected_at) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($request->status === 'approved' && $request->approval_note)
                                <div class="alert alert-success alert-sm mb-2">
                                    <i class="fa-solid fa-check-circle me-1"></i>
                                    <strong>Approved</strong>
                                </div>
                                @if($request->remarks)
                                    <small class="text-muted d-block">{{ $request->remarks }}</small>
                                @endif
                            @elseif($request->status === 'rejected')
                                <div class="alert alert-danger alert-sm mb-2">
                                    <i class="fa-solid fa-times-circle me-1"></i>
                                    <strong>Rejected</strong>
                                </div>
                                @if($request->rejection_reason)
                                    <small class="d-block"><strong>Reason:</strong></small>
                                    <small class="text-danger d-block">{{ $request->rejection_reason }}</small>
                                @endif
                            @else
                                <small class="text-muted">Pending approval...</small>
                            @endif
                        </td>
                        <td>{{ \App\Helpers\DateHelper::formatDate($request->created_at) }}</td>
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
