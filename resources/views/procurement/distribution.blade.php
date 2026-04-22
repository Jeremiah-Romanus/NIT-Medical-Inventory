@extends('layouts.layout')

@section('title', 'Distribution')
@section('page-title', 'Distribution Log')
@section('page-subtitle', 'Record and review issued medicines across departments and wards.')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">Quick entry</h5>
                <div class="p-4 rounded-4" style="background: rgba(96,165,250,.08);">
                    <p class="mb-0 text-secondary">
                        This section is ready for the next step: a real distribution form tied to request approval and stock deduction.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded-4" style="background: rgba(110,231,183,.08);">
                            <div class="text-secondary small">Records</div>
                            <div class="fs-3 fw-bold">{{ $distributions->count() }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-4" style="background: rgba(251,191,36,.08);">
                            <div class="text-secondary small">Latest recipient</div>
                            <div class="fs-6 fw-bold">{{ $distributions->first()->distributed_to ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                            <div class="text-secondary small">Latest issue</div>
                            <div class="fs-6 fw-bold">{{ $distributions->first() ? $distributions->first()->quantity_issued . ' units' : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Distribution history</h5>
        <span class="text-secondary small">Recent issues from stock</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Distributed To</th>
                    <th>Quantity</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($distributions as $distribution)
                    <tr>
                        <td>{{ $distribution->medicine }}</td>
                        <td>{{ $distribution->distributed_to }}</td>
                        <td>{{ $distribution->quantity_issued }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($distribution->transaction_date)->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">No distributions recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
