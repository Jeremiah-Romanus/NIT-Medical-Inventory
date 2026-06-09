@extends('layouts.layout')

@section('title', __('nav.approve'))
@section('page-title', __('nav.approve'))
@section('page-subtitle', __('request.pending'))

@section('content')
<div class="card mb-4" x-data="{ search: '' }">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded-4" style="background: rgba(96,165,250,.08);">
                    <div class="text-secondary small">{{ __('request.title') }}</div>
                    <div class="fs-3 fw-bold">{{ $requests->count() }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-4" style="background: rgba(251,191,36,.08);">
                    <div class="text-secondary small">{{ __('request.status.pending') }}</div>
                    <div class="fs-3 fw-bold">{{ $requests->where('status', 'pending')->count() }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-4" style="background: rgba(110,231,183,.08);">
                    <div class="text-secondary small">{{ __('request.status.approved') }}</div>
                    <div class="fs-3 fw-bold">{{ $requests->where('status', 'approved')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card" x-data="{
    search: '',
    modalOpen: false,
    targetId: null,
    action: null,
    remarks: '',
    openConfirm(id, act) {
        this.targetId = id;
        this.action = act;
        this.remarks = '';
        this.modalOpen = true;
    },
    submit() {
        if (!this.targetId) return;
        if (this.action === 'approve') {
            document.getElementById('approve-form-' + this.targetId)?.submit();
        } else {
            const f = document.getElementById('reject-form-' + this.targetId);
            if (f) {
                f.querySelector('input[name=remarks]').value = this.remarks || '';
                f.submit();
            }
        }
    }
}">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title">{{ __('request.pending') }}</h5>
        <input type="text" x-model="search" class="form-control form-control-sm table-filter-input" placeholder="{{ __('common.search') }}">
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>{{ __('request.requester') }}</th>
                    <th>{{ __('request.medicine') }}</th>
                    <th>{{ __('request.quantity') }}</th>
                    <th>{{ __('medicine.status') }}</th>
                    <th>{{ __('request.remarks') }}</th>
                    <th>{{ __('user.joined') }}</th>
                    <th>{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                    <tr x-show="!search || '{{ strtolower($request->user->name) }} {{ strtolower($request->medicine->name) }}'.includes(search.toLowerCase())" class="fade-in">
                        <td>{{ $request->user->name }}</td>
                        <td>{{ $request->medicine->name }}</td>
                        <td>{{ $request->requested_quantity }}</td>
                        <td>
                            <span class="badge
                                @if($request->status === 'approved') bg-success
                                @elseif($request->status === 'rejected') bg-danger
                                @else bg-warning text-dark
                                @endif">
                                {{ __($request->status === 'pending' ? 'request.status.pending' : ($request->status === 'approved' ? 'request.status.approved' : 'request.status.rejected')) }}
                            </span>
                        </td>
                        <td>{{ $request->remarks ?: '—' }}</td>
                        <td>{{ \App\Helpers\DateHelper::formatDate($request->created_at) }}</td>
                        <td>
                            @if($request->status === 'pending')
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <form id="approve-form-{{ $request->id }}" method="POST" action="{{ route('requests.approve', $request->id) }}" style="display:none;">
                                        @csrf
                                    </form>
                                    <button type="button" class="btn btn-sm btn-success" @click="openConfirm({{ $request->id }}, 'approve')">
                                        <i class="fa-solid fa-check me-1"></i>{{ __('request.approve') }}
                                    </button>

                                    <form id="reject-form-{{ $request->id }}" method="POST" action="{{ route('requests.reject', $request->id) }}" style="display:none;">
                                        @csrf
                                        <input type="hidden" name="remarks" value="">
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" @click="openConfirm({{ $request->id }}, 'reject')">
                                        <i class="fa-solid fa-xmark me-1"></i>{{ __('request.reject') }}
                                    </button>
                                </div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">{{ __('request.no_requests') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
                    </div>

<!-- Confirmation modal -->
<div x-show="modalOpen" x-cloak style="position:fixed;inset:0;display:grid;place-items:center;background:rgba(0,0,0,0.4);z-index:1050;">
  <div class="card p-3" @click.away="modalOpen=false" style="width:540px;max-width:90%;">
    <div class="card-body">
      <h5 class="card-title">{{ __('request.title') }}</h5>
      <p class="text-muted" x-text="action === 'approve' ? '{{ __('request.approve') }}' : '{{ __('request.reject') }}'"></p>
      <div class="mb-3" x-show="action === 'reject'">
        <label class="form-label">{{ __('request.remarks') }}</label>
        <input x-model="remarks" class="form-control form-control-sm" placeholder="{{ __('request.remarks') }}">
      </div>
      <div class="d-flex justify-content-end gap-2">
        <button class="btn btn-sm btn-secondary" type="button" @click="modalOpen=false">{{ __('common.cancel') }}</button>
        <button class="btn btn-sm btn-primary" type="button" @click="submit()">{{ __('common.save') }}</button>
      </div>
    </div>
  </div>
</div>

@endsection
