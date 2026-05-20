@extends('layouts.layout')

@section('title', 'Medicines')

@section('page-title', 'Inventory Management')

@section('content')
<style>
    .inventory-table th {
        white-space: nowrap;
        font-size: 0.86rem;
    }

    .inventory-table td {
        vertical-align: middle;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge.active {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }

    .status-badge.expiring {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fcd34d;
    }

    .status-badge.expired {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }
</style>
<div class="container-fluid">
    <!-- Alerts for Expired Medicines -->
    @php
        $expiredCount = $medicines->filter(fn($m) => $m->isExpired())->count();
        $expiringCount = $medicines->filter(fn($m) => $m->isExpiringSoon() && !$m->isExpired())->count();
    @endphp

    @if($expiredCount > 0 || $expiringCount > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Warning:</strong> 
            @if($expiredCount > 0)
                You have <strong>{{ $expiredCount }}</strong> expired {{ $expiredCount === 1 ? 'medicine' : 'medicines' }}
                @if($expiringCount > 0) and @endif
            @endif
            @if($expiringCount > 0)
                <strong>{{ $expiringCount }}</strong> {{ $expiringCount === 1 ? 'medicine' : 'medicines' }} expiring soon
            @endif
            . Please review them immediately.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Medicine Table Card -->
    <div class="card">
        <div class="card-header">
            <div>
                <h5 class="card-title">
                <i class="fas fa-pills"></i> Medicine Stock List
                </h5>
                <div class="text-secondary small mt-1">{{ $medicines->count() }} medicine types currently in the system.</div>
            </div>
            
            <!-- Add Medicine Button - Only for Procurement Officers -->
            @if (auth()->user()->role === 'procurement')
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                    <i class="fas fa-plus"></i> Add Medicine
                </button>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover inventory-table">
                <thead>
                    <tr>
                        <th>Medical ID</th>
                        <th>Generic Name</th>
                        <th>Formulation / Strength</th>
                        <th>Batch Number</th>
                        <th>Stock Level</th>
                        <th>Unit Price</th>
                        <th>Stored Date</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $medicine)
                        <tr @class([
                            'expired' => $medicine->isExpired(),
                            'expiring-soon' => $medicine->isExpiringSoon() && !$medicine->isExpired()
                        ])>
                            <td>{{ $medicine->medical_id }}</td>
                            <td>
                                <strong>{{ $medicine->name }}</strong>
                            </td>
                            <td>{{ $medicine->formulation_strength }}</td>
                            <td>{{ $medicine->batch_number }}</td>
                            <td>
                                <span class="badge @if($medicine->quantity < 50) bg-danger @elseif($medicine->quantity < 100) bg-warning @else bg-success @endif">
                                    {{ $medicine->quantity }} units
                                </span>
                            </td>
                            <td>TZS {{ number_format($medicine->unit_price, 2) }}</td>
                            <td>
                                <small>{{ optional($medicine->stored_date) ? \App\Helpers\DateHelper::formatDate($medicine->stored_date) : 'N/A' }}</small>
                            </td>
                            <td>
                                <small>{{ \App\Helpers\DateHelper::formatDate($medicine->expiry_date) }}</small>
                            </td>
                            <td>
                                @if($medicine->isExpired())
                                    <span class="status-badge expired">
                                        <i class="fas fa-times-circle"></i> EXPIRED
                                    </span>
                                @elseif($medicine->isExpiringSoon())
                                    <span class="status-badge expiring">
                                        <i class="fas fa-clock"></i> EXPIRING SOON
                                    </span>
                                @else
                                    <span class="status-badge active">
                                        <i class="fas fa-check-circle"></i> ACTIVE
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('medicines.show', $medicine->id) }}" class="btn btn-sm btn-info text-white" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if (auth()->user()->role === 'procurement')
                                    <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('medicines.destroy', $medicine->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-inbox" style="font-size: 30px; color: #ccc;"></i>
                                <p class="mt-2 text-muted">No medicines found. @if(auth()->user()->role === 'procurement') <a href="#addMedicineModal" data-bs-toggle="modal">Add one now</a>.@endif</p>
                            </td>
                        </tr>
                    @endempty
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Add Medicine Modal - Only for Procurement Officers -->
@if (auth()->user()->role === 'procurement')
<div class="modal fade" id="addMedicineModal" tabindex="-1" aria-labelledby="addMedicineLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMedicineLabel">
                    <i class="fas fa-plus"></i> Add New Medicine
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('medicines.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="medicalId" class="form-label">Medical ID *</label>
                        <input type="text" class="form-control @error('medical_id') is-invalid @enderror" name="medical_id" id="medicalId" placeholder="e.g., MED-001" value="{{ old('medical_id') }}" required>
                        @error('medical_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="medicineName" class="form-label">Generic Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="medicineName" placeholder="e.g., Paracetamol" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="formulationStrength" class="form-label">Formulation / Strength *</label>
                        <input type="text" class="form-control @error('formulation_strength') is-invalid @enderror" name="formulation_strength" id="formulationStrength" placeholder="e.g., Tablet 500mg" value="{{ old('formulation_strength') }}" required>
                        @error('formulation_strength')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="batchNumber" class="form-label">Batch Number *</label>
                        <input type="text" class="form-control @error('batch_number') is-invalid @enderror" name="batch_number" id="batchNumber" placeholder="e.g., BATCH-001" value="{{ old('batch_number') }}" required>
                        @error('batch_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Quantity *</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" id="quantity" placeholder="0" value="{{ old('quantity') }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="unitPrice" class="form-label">Unit Price (TZS) *</label>
                            <input type="number" class="form-control @error('unit_price') is-invalid @enderror" name="unit_price" id="unitPrice" placeholder="0.00" value="{{ old('unit_price') }}" step="0.01" required>
                            @error('unit_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="storedDate" class="form-label">Stored Date *</label>
                        <input type="date" class="form-control @error('stored_date') is-invalid @enderror" name="stored_date" id="storedDate" value="{{ old('stored_date', now()->format('Y-m-d')) }}" required>
                        @error('stored_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="expiryDate" class="form-label">Expiry Date *</label>
                        <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" name="expiry_date" id="expiryDate" value="{{ old('expiry_date') }}" required>
                        @error('expiry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    // JavaScript to check expiry dates and apply styles dynamically
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const sixMonthsLater = new Date(today.getFullYear(), today.getMonth() + 6, today.getDate());
        
        document.querySelectorAll('table tbody tr').forEach(row => {
            // Skip if already has expiry class
            if (row.classList.contains('expired') || row.classList.contains('expiring-soon')) {
                return;
            }
            
            const expiryDateText = row.querySelector('td:nth-child(8) small').textContent.trim();
            const expiryDate = new Date(expiryDateText);
            
            if (expiryDate < today) {
                row.classList.add('expired');
            } else if (expiryDate < sixMonthsLater) {
                row.classList.add('expiring-soon');
            }
        });
    });
</script>
@endsection
