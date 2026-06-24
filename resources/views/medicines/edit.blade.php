@extends('layouts.layout')

@section('title', 'Edit Medicine')
@section('page-title', 'Edit Medicine')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-pen-to-square"></i> Update Medicine</h5>
                <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-secondary">Back to Stock</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('medicines.update', $medicine->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Medical ID</label>
                            <input type="text" name="medical_id" class="form-control @error('medical_id') is-invalid @enderror" value="{{ old('medical_id', $medicine->medical_id) }}" required>
                            @error('medical_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Medicine Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $medicine->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Formulation / Strength</label>
                            <input type="text" name="formulation_strength" class="form-control @error('formulation_strength') is-invalid @enderror" value="{{ old('formulation_strength', $medicine->formulation_strength) }}" required>
                            @error('formulation_strength')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Batch Number</label>
                            <input type="text" name="batch_number" class="form-control @error('batch_number') is-invalid @enderror" value="{{ old('batch_number', $medicine->batch_number) }}" required>
                            @error('batch_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" min="0" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $medicine->quantity) }}" required>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Pharmacy Quantity</label>
                            <input type="number" min="0" name="pharmacy_quantity" class="form-control @error('pharmacy_quantity') is-invalid @enderror" value="{{ old('pharmacy_quantity', $medicine->pharmacy_quantity) }}">
                            @error('pharmacy_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Unit Price</label>
                            <input type="number" step="0.01" min="0" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror" value="{{ old('unit_price', $medicine->unit_price) }}" required>
                            @error('unit_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stored Date</label>
                            <input type="text" name="stored_date" class="form-control @error('stored_date') is-invalid @enderror" value="{{ old('stored_date', \App\Helpers\DateHelper::formatDate($medicine->stored_date)) }}" placeholder="DD/MM/YYYY" inputmode="numeric" pattern="\d{2}/\d{2}/\d{4}" required>
                            <div class="form-text">Use DD/MM/YYYY format.</div>
                            @error('stored_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expiry Date</label>
                            <input type="text" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date', \App\Helpers\DateHelper::formatDate($medicine->expiry_date)) }}" placeholder="DD/MM/YYYY" inputmode="numeric" pattern="\d{2}/\d{2}/\d{4}" required>
                            <div class="form-text">Use DD/MM/YYYY format.</div>
                            @error('expiry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Medicine</button>
                        <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
