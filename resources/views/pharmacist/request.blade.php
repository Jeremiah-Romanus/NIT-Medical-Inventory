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
                <form>
                    <div class="mb-3">
                        <label class="form-label">Medicine</label>
                        <select class="form-select">
                            <option selected disabled>Select medicine</option>
                            @forelse($medicines as $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->name }} - {{ $medicine->category }}</option>
                            @empty
                                <option disabled>No medicines available</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Requested quantity</label>
                            <input type="number" class="form-control" placeholder="e.g. 100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <select class="form-select">
                                <option>Normal</option>
                                <option>Urgent</option>
                                <option>Critical</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" rows="4" placeholder="Add usage notes or justification"></textarea>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="button" class="btn btn-primary">Send Request</button>
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
                        Keep this flow lean. We can hook it to the backend next.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
