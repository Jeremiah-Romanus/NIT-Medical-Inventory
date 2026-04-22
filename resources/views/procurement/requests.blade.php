@extends('layouts.layout')

@section('title', 'Request Approvals')
@section('page-title', 'Request Approvals')
@section('page-subtitle', 'Review pharmacist requests, approve urgent needs, and keep stock movement controlled.')

@section('content')
<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                    <div class="text-secondary small">Total requests</div>
                    <div class="fs-3 fw-bold">{{ $requests->count() }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-4" style="background: rgba(251,191,36,.08);">
                    <div class="text-secondary small">Pending</div>
                    <div class="fs-3 fw-bold">{{ $requests->where('status', 'pending')->count() }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-4" style="background: rgba(110,231,183,.08);">
                    <div class="text-secondary small">Approved</div>
                    <div class="fs-3 fw-bold">{{ $requests->where('status', 'approved')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Request queue</h5>
        <span class="text-secondary small">Most recent at the top</span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Requester</th>
                    <th>Medicine</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                    <tr>
                        <td>{{ $request->requester }}</td>
                        <td>{{ $request->medicine }}</td>
                        <td>{{ $request->quantity }}</td>
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
                        <td>{{ \Illuminate\Support\Carbon::parse($request->created_at)->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">No requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
