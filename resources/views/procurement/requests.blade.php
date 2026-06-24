@extends('layouts.layout')

@section('title', __('nav.approve'))
@section('page-title', __('nav.approve'))
@section('page-subtitle', 'Review pending medicine requests and decide whether to approve or reject them.')

@section('content')
<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                    <div class="text-secondary small">Medicine Requests</div>
                    <div class="fs-3 fw-bold">{{ $totalRequestsCount }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-4" style="background: rgba(251,191,36,.08);">
                    <div class="text-secondary small">Pending</div>
                    <div class="fs-3 fw-bold">{{ $pendingRequestsCount }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-4" style="background: rgba(110,231,183,.08);">
                    <div class="text-secondary small">Approved</div>
                    <div class="fs-3 fw-bold">{{ $approvedRequestsCount }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-1">Pending Requests</h5>
            <div class="text-secondary small">Approve stock transfers or reject with a reason that is sent to the pharmacist.</div>
        </div>
        <input type="search" id="request-search" class="form-control form-control-sm table-filter-input" placeholder="{{ __('common.search') }}" style="max-width: 250px;">
    </div>

    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Requester</th>
                    <th>Medicine</th>
                    <th class="text-end">Procurement Qty</th>
                    <th class="text-end">Requested quantity</th>
                    <th>Status</th>
                    <th>Remarks (optional)</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="request-table-body">
                @forelse($requests as $request)
                    @php
                        $searchableText = strtolower(
                            trim(($request->requester ?? '') . ' ' . ($request->medicine ?? '') . ' ' . ($request->remarks ?? ''))
                        );
                    @endphp
                    <tr data-search="{{ $searchableText }}" class="fade-in">
                        <td>{{ $request->requester ?? __('common.unknown') }}</td>
                        <td>
                            <div class="fw-semibold">{{ $request->medicine ?? __('common.unknown') }}</div>
                            <small class="text-secondary">{{ $request->batch_number }}</small>
                        </td>
                        <td class="text-end">{{ $request->procurement_quantity ?? 0 }}</td>
                        <td class="text-end">{{ $request->quantity }}</td>
                        <td>
                            <span class="badge bg-warning text-dark">Pending</span>
                        </td>
                        <td>{{ $request->remarks ?: '-' }}</td>
                        <td>{{ \App\Helpers\DateHelper::formatDate($request->created_at) }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <form method="POST" action="{{ route('requests.approve', $request->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fa-solid fa-check me-1"></i>Approve
                                    </button>
                                </form>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#rejectRequestModal"
                                    data-action="{{ route('requests.reject', $request->id) }}"
                                    data-requester="{{ e($request->requester ?? 'Unknown') }}"
                                    data-medicine="{{ e($request->medicine ?? 'Unknown') }}"
                                    data-quantity="{{ $request->quantity }}"
                                >
                                    <i class="fa-solid fa-xmark me-1"></i>Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-secondary py-4">{{ __('request.no_requests') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="rejectRequestModal" tabindex="-1" aria-labelledby="rejectRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectRequestModalLabel">Reject Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="" id="rejectRequestForm">
                @csrf
                <div class="modal-body">
                    <p class="text-secondary mb-3" id="rejectRequestSummary">Provide a reason for rejecting this request.</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label fw-semibold">Rejection reason <span class="text-danger">*</span></label>
                        <textarea
                            id="rejection_reason"
                            name="rejection_reason"
                            class="form-control"
                            rows="4"
                            placeholder="Explain why this request was rejected"
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-paper-plane me-1"></i>Send Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const searchInput = document.getElementById('request-search');
        const rows = Array.from(document.querySelectorAll('#request-table-body tr[data-search]'));
        const rejectModal = document.getElementById('rejectRequestModal');
        const rejectForm = document.getElementById('rejectRequestForm');
        const rejectSummary = document.getElementById('rejectRequestSummary');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const term = this.value.trim().toLowerCase();

                rows.forEach(function (row) {
                    row.style.display = !term || row.dataset.search.includes(term) ? '' : 'none';
                });
            });
        }

        if (rejectModal && rejectForm) {
            rejectModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const action = button?.getAttribute('data-action') || '';
                const requester = button?.getAttribute('data-requester') || 'Unknown requester';
                const medicine = button?.getAttribute('data-medicine') || 'Unknown medicine';
                const quantity = button?.getAttribute('data-quantity') || '0';

                rejectForm.action = action;
                rejectSummary.textContent = `Reject ${quantity} units of ${medicine} requested by ${requester}.`;

                const textarea = document.getElementById('rejection_reason');
                if (textarea) {
                    textarea.value = '';
                }
            });
        }
    })();
</script>
@endsection
