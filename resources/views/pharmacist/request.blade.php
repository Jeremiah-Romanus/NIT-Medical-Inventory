@extends('layouts.layout')

@section('title', 'Submit Request')
@section('page-title', 'Submit Medicine Request')
@section('page-subtitle', 'Create a request to procurement with the exact stock you need.')

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

                <form method="POST" action="{{ route('requests.store') }}" id="medicine-request-form">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Request ID</label>
                            <input type="text" id="request-id" class="form-control" value="" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Request Date</label>
                            <input type="text" class="form-control" value="{{ now()->format('d/m/Y') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Requested By</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
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
                                        data-formulation="{{ $medicine->formulation_strength }}"
                                        data-stock="{{ $medicine->quantity }}"
                                        @selected(old('medicine_id') == $medicine->id)
                                    >
                                        {{ $medicine->medical_id }} - {{ $medicine->name }} ({{ $medicine->formulation_strength }})
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
                                id="request-remarks"
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

                    <div class="mt-4">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0">Request details</h6>
                                    <span class="text-secondary small">Preview before submit</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Field</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="w-35">Request ID</th>
                                                <td id="detail-request-id">-</td>
                                            </tr>
                                            <tr>
                                                <th>Request Date</th>
                                                <td>{{ now()->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Requested By</th>
                                                <td>{{ auth()->user()->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Medicine Name &amp; Strength</th>
                                                <td id="detail-medicine-name">Select medicine first</td>
                                            </tr>
                                            <tr>
                                                <th>Formulation</th>
                                                <td id="detail-formulation">Select medicine first</td>
                                            </tr>
                                            <tr>
                                                <th>Current Pharmacy Stock</th>
                                                <td id="detail-stock">Select medicine first</td>
                                            </tr>
                                            <tr>
                                                <th>Quantity Requested</th>
                                                <td id="detail-quantity-requested">Enter quantity needed</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                    <th>Remarks</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myRequests as $request)
                    <tr>
                        <td>{{ $request->medicine->medical_id }} - {{ $request->medicine->name }}</td>
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

<script>
    const medicineSelect = document.getElementById('medicine-select');
    const requestedQuantityInput = document.getElementById('requested-quantity');
    const requestIdField = document.getElementById('request-id');
    const detailMedicineName = document.getElementById('detail-medicine-name');
    const detailFormulation = document.getElementById('detail-formulation');
    const detailStock = document.getElementById('detail-stock');
    const detailQuantityRequested = document.getElementById('detail-quantity-requested');

    function generateRequestId() {
        const now = new Date();
        const datePart = now.getFullYear().toString()
            + String(now.getMonth() + 1).padStart(2, '0')
            + String(now.getDate()).padStart(2, '0');
        const timePart = String(now.getHours()).padStart(2, '0')
            + String(now.getMinutes()).padStart(2, '0')
            + String(now.getSeconds()).padStart(2, '0');
        const randomPart = Math.floor(1000 + Math.random() * 9000);

        return 'REQ-' + datePart + '-' + timePart + '-' + randomPart;
    }

    function setText(element, value, fallback) {
        element.textContent = value && String(value).trim() !== '' ? value : fallback;
    }

    function updateMedicineDetails() {
        const option = medicineSelect?.selectedOptions[0];
        const hasMedicine = option && option.value;

        if (!hasMedicine) {
            setText(detailMedicineName, '', 'Select medicine first');
            setText(detailFormulation, '', 'Select medicine first');
            setText(detailStock, '', 'Select medicine first');
            return;
        }

        const medicineName = option.dataset.name + ' ' + option.dataset.strength;
        const stock = Number(option.dataset.stock || 0).toLocaleString() + ' units';

        setText(detailMedicineName, medicineName, 'Select medicine first');
        setText(detailFormulation, option.dataset.formulation, 'Select medicine first');
        setText(detailStock, stock, 'Select medicine first');
    }

    function updateQuantityDetail() {
        setText(detailQuantityRequested, requestedQuantityInput?.value || '', 'Enter quantity needed');
    }

    if (requestIdField) {
        requestIdField.value = generateRequestId();
    }

    medicineSelect?.addEventListener('change', updateMedicineDetails);
    requestedQuantityInput?.addEventListener('input', updateQuantityDetail);

    updateMedicineDetails();
    updateQuantityDetail();
</script>
@endsection
