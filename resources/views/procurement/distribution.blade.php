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
                <form method="POST" action="{{ route('procurement.distribution.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="medicine_id" class="form-label">Medicine</label>
                        <select id="medicine_id" name="medicine_id" class="form-select @error('medicine_id') is-invalid @enderror" required>
                            <option value="">Select medicine</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}" @selected(old('medicine_id') == $medicine->id)>
                                    {{ $medicine->name }} | Stock: {{ $medicine->quantity }}
                                </option>
                            @endforeach
                        </select>
                        @error('medicine_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="distributed_to" class="form-label">Distributed to</label>
                        <input
                            type="text"
                            id="distributed_to"
                            name="distributed_to"
                            value="{{ old('distributed_to') }}"
                            class="form-control @error('distributed_to') is-invalid @enderror"
                            placeholder="Enter recipient department or unit"
                            required
                        >
                        @error('distributed_to')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="quantity_issued" class="form-label">Quantity issued</label>
                            <input
                                type="number"
                                id="quantity_issued"
                                name="quantity_issued"
                                value="{{ old('quantity_issued') }}"
                                min="1"
                                class="form-control @error('quantity_issued') is-invalid @enderror"
                                placeholder="Enter issued quantity"
                                required
                            >
                            @error('quantity_issued')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="transaction_date" class="form-label">Transaction date</label>
                            <input
                                type="datetime-local"
                                id="transaction_date"
                                name="transaction_date"
                                value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}"
                                class="form-control @error('transaction_date') is-invalid @enderror"
                                required
                            >
                            @error('transaction_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 w-100">Record Distribution</button>
                </form>
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
                        <td>{{ $distribution->medicine->name }}</td>
                        <td>{{ $distribution->distributed_to }}</td>
                        <td>{{ $distribution->quantity_issued }}</td>
                        <td>{{ $distribution->transaction_date->format('M d, Y H:i') }}</td>
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
