@extends('layouts.layout')

@section('title', 'Medicines')

@section('page-title', 'Inventory Management')

@section('content')
<div class="container-fluid">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
            <h5 class="card-title">
                <i class="fas fa-pills"></i> Medicine Stock List
            </h5>
            
            <!-- Add Medicine Button - Only for Procurement Officers -->
            @if (auth()->user()->role === 'procurement')
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                    <i class="fas fa-plus"></i> Add Medicine
                </button>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Batch Number</th>
                        <th>Stock Level</th>
                        <th>Unit Price</th>
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
                            <td>
                                <strong>{{ $medicine->name }}</strong>
                            </td>
                            <td>{{ $medicine->category }}</td>
                            <td>{{ $medicine->batch_number }}</td>
                            <td>
                                <span class="badge @if($medicine->quantity < 50) bg-danger @elseif($medicine->quantity < 100) bg-warning @else bg-success @endif">
                                    {{ $medicine->quantity }} units
                                </span>
                            </td>
                            <td>Ksh {{ number_format($medicine->unit_price, 2) }}</td>
                            <td>
                                <small>{{ $medicine->expiry_date->format('Y-m-d') }}</small>
                            </td>
                            <td>
                                @if($medicine->isExpired())
                                    <span class="badge badge-expired">
                                        <i class="fas fa-times-circle"></i> EXPIRED
                                    </span>
                                @elseif($medicine->isExpiringSoon())
                                    <span class="badge badge-expiring">
                                        <i class="fas fa-clock"></i> EXPIRING SOON
                                    </span>
                                @else
                                    <span class="badge badge-active">
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
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox" style="font-size: 30px; color: #ccc;"></i>
                                <p class="mt-2 text-muted">No medicines found. @if(auth()->user()->role === 'procurement') <a href="#addMedicineModal" data-bs-toggle="modal">Add one now</a>.@endif</p>
                            </td>
                        </tr>
                    @endempty
                </tbody>
            </table>
        </div>

        <!-- Table Footer with Pagination -->
        <div style="padding: 20px; text-align: center; border-top: 1px solid #e9ecef;">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
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
                        <label for="medicineName" class="form-label">Medicine Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="medicineName" placeholder="e.g., Paracetamol" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category *</label>
                        <select class="form-select @error('category') is-invalid @enderror" name="category" id="category" required>
                            <option selected disabled value="">Select Category</option>
                            <option value="Analgesic" @selected(old('category') == 'Analgesic')>Analgesic</option>
                            <option value="Antibiotic" @selected(old('category') == 'Antibiotic')>Antibiotic</option>
                            <option value="Antifungal" @selected(old('category') == 'Antifungal')>Antifungal</option>
                            <option value="Antiviral" @selected(old('category') == 'Antiviral')>Antiviral</option>
                            <option value="Diabetes" @selected(old('category') == 'Diabetes')>Diabetes</option>
                            <option value="Heart" @selected(old('category') == 'Heart')>Heart</option>
                            <option value="Antimalarial" @selected(old('category') == 'Antimalarial')>Antimalarial</option>
                        </select>
                        @error('category')
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
                            <label for="unitPrice" class="form-label">Unit Price (Ksh) *</label>
                            <input type="number" class="form-control @error('unit_price') is-invalid @enderror" name="unit_price" id="unitPrice" placeholder="0.00" value="{{ old('unit_price') }}" step="0.01" required>
                            @error('unit_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
        const threeMonthsLater = new Date(today.getFullYear(), today.getMonth() + 3, today.getDate());
        
        document.querySelectorAll('table tbody tr').forEach(row => {
            // Skip if already has expiry class
            if (row.classList.contains('expired') || row.classList.contains('expiring-soon')) {
                return;
            }
            
            const expiryDateText = row.querySelector('td:nth-child(6) small').textContent.trim();
            const expiryDate = new Date(expiryDateText);
            
            if (expiryDate < today) {
                row.classList.add('expired');
            } else if (expiryDate < threeMonthsLater) {
                row.classList.add('expiring-soon');
            }
        });
    });
</script>
@endsection
