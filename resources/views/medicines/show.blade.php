@extends('layouts.layout')

@section('title', 'Medicine Details')
@section('page-title', 'Medicine Details')

@section('content')
<style>
    .medicine-summary {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        margin-bottom: 22px;
    }

    .medicine-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0 0 6px;
    }

    .medicine-subtitle {
        color: #64748b;
        margin: 0;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .detail-card {
        padding: 16px 18px;
        border: 1px solid #d9ebf7;
        background: #ffffff;
        border-radius: 14px;
    }

    .detail-card strong {
        display: block;
        margin-bottom: 8px;
        font-size: 0.88rem;
        letter-spacing: 0.02em;
        color: #475569;
        text-transform: uppercase;
    }

    .detail-card .value {
        font-size: 1.02rem;
        font-weight: 600;
        color: #0f172a;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 0.88rem;
        font-weight: 700;
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

    @media (max-width: 767.98px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <div class="card">
            <div class="card-body p-4 p-lg-5">
                <div class="medicine-summary">
                    <div>
                        <h2 class="medicine-title"><i class="fas fa-vial-circle-check me-2"></i>{{ $medicine->name }}</h2>
                        <p class="medicine-subtitle">{{ $medicine->medical_id }} | {{ $medicine->formulation_strength }}</p>
                    </div>
                    <a href="{{ route('medicines.index') }}" class="btn btn-secondary">Back to Stock</a>
                </div>

                <div class="detail-grid">
                    <div class="detail-card">
                        <strong>Medical ID</strong>
                        <div class="value">{{ $medicine->medical_id }}</div>
                    </div>
                    <div class="detail-card">
                        <strong>Formulation / Strength</strong>
                        <div class="value">{{ $medicine->formulation_strength }}</div>
                    </div>
                    <div class="detail-card">
                        <strong>Generic Name</strong>
                        <div class="value">{{ $medicine->name }}</div>
                    </div>
                    <div class="detail-card">
                        <strong>Batch Number</strong>
                        <div class="value">{{ $medicine->batch_number }}</div>
                    </div>
                    <div class="detail-card">
                        <strong>Stock Level</strong>
                        <div class="value">{{ number_format($medicine->quantity) }} units</div>
                    </div>
                    <div class="detail-card">
                        <strong>Unit Price</strong>
                        <div class="value">TZS {{ number_format($medicine->unit_price, 2) }}</div>
                    </div>
                    <div class="detail-card">
                        <strong>Stored Date</strong>
                        <div class="value">{{ optional($medicine->stored_date) ? \App\Helpers\DateHelper::formatDate($medicine->stored_date) : 'N/A' }}</div>
                    </div>
                    <div class="detail-card">
                        <strong>Months Remaining in Store</strong>
                        <div class="value">
                            @if($medicine->isExpired())
                                Expired
                            @else
                                @php
                                    $monthsRemaining = max(0, (int) floor(now()->floatDiffInMonths($medicine->expiry_date)));
                                @endphp
                                {{ $monthsRemaining }} {{ $monthsRemaining === 1 ? 'month' : 'months' }} remaining
                            @endif
                        </div>
                    </div>
                    <div class="detail-card">
                        <strong>Expiry Date</strong>
                        <div class="value">{{ \App\Helpers\DateHelper::formatDate($medicine->expiry_date) }}</div>
                    </div>
                    <div class="detail-card">
                        <strong>Status</strong>
                        <div class="value">
                            @if($medicine->isExpired())
                                <span class="status-badge expired"><i class="fas fa-times-circle"></i>Expired</span>
                            @elseif($medicine->isExpiringSoon())
                                <span class="status-badge expiring"><i class="fas fa-clock"></i>Expiring Soon</span>
                            @else
                                <span class="status-badge active"><i class="fas fa-check-circle"></i>Active</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
