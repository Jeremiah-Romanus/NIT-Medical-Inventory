@extends('layouts.layout')

@section('title', 'Medicine Details')
@section('page-title', 'Medicine Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-vial-circle-check"></i> {{ $medicine->name }}</h5>
                <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-secondary">Back to Stock</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Category</strong>
                        <div>{{ $medicine->category }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Batch Number</strong>
                        <div>{{ $medicine->batch_number }}</div>
                    </div>
                    <div class="col-md-4">
                        <strong>Quantity</strong>
                        <div>{{ $medicine->quantity }}</div>
                    </div>
                    <div class="col-md-4">
                        <strong>Unit Price</strong>
                        <div>Ksh {{ number_format($medicine->unit_price, 2) }}</div>
                    </div>
                    <div class="col-md-4">
                        <strong>Expiry Date</strong>
                        <div>{{ $medicine->expiry_date->format('Y-m-d') }}</div>
                    </div>
                    <div class="col-md-12">
                        <strong>Status</strong>
                        <div class="mt-1">
                            @if($medicine->isExpired())
                                <span class="badge badge-expired">Expired</span>
                            @elseif($medicine->isExpiringSoon())
                                <span class="badge badge-expiring">Expiring Soon</span>
                            @else
                                <span class="badge badge-active">Active</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
