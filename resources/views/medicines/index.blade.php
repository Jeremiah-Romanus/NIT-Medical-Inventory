@extends('layouts.layout')

@section('title', __('medicine.title'))
@section('page-title', __('medicine.title'))
@section('page-subtitle', __('medicine.total') . ': ' . $medicines->total())

@section('content')
<style>
    .inventory-table th {
        white-space: nowrap;
        font-size: 0.86rem;
    }

    .inventory-table td {
        vertical-align: middle;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
        white-space: nowrap;
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

    .stock-badge {
        border-radius: 999px;
        padding: 0.45rem 0.75rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid">
    <!-- Alerts for Expired Medicines -->

    @if($expiredCount > 0 || $expiringCount > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>{{ __('alert.warning') }}:</strong> 
            @if($expiredCount > 0)
                {{ __('dashboard.expired') }}: <strong>{{ $expiredCount }}</strong>
                @if($expiringCount > 0) & @endif
            @endif
            @if($expiringCount > 0)
                {{ __('dashboard.expiring_soon') }}: <strong>{{ $expiringCount }}</strong>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Medicine Table Card -->
    <div class="card">
        <div class="card-header">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
              <h5 class="card-title"><i class="fas fa-pills"></i> {{ __('medicine.title') }}</h5>
              <div class="text-secondary small mt-1">{{ $medicines->total() }} {{ __('medicine.total') }}</div>
            </div>
            <div class="d-flex gap-2 align-items-center">
              <form method="GET" action="{{ route('medicines.index') }}" class="d-flex gap-2 align-items-center" role="search">
                <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="{{ __('common.search') }} {{ __('medicine.name') }}...">
                <select name="status" class="form-select form-select-sm">
                  <option value="">{{ __('common.all') }}</option>
                  <option value="active" @selected(request('status') === 'active')>{{ __('medicine.active') }}</option>
                  <option value="expiring_soon" @selected(request('status') === 'expiring_soon')>{{ __('medicine.expiring_soon') }}</option>
                  <option value="expired" @selected(request('status') === 'expired')>{{ __('medicine.expired') }}</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">{{ __('common.filter') }}</button>
              </form>
              @if (auth()->user()->role === 'procurement' || auth()->user()->role === 'admin')
                <a href="{{ route('medicines.create') }}" class="btn btn-primary btn-sm">
                  <i class="fas fa-plus"></i> {{ __('medicine.create') }}
                </a>
              @endif
            </div>
          </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover inventory-table">
                <thead>
                    <tr>
                        <th>{{ __('medicine.medical_id') }}</th>
                        <th>{{ __('medicine.name') }}</th>
                        <th>{{ __('medicine.formulation') }}</th>
                        <th>{{ __('medicine.batch') }}</th>
                        <th>{{ __('medicine.quantity') }}</th>
                        <th>{{ __('medicine.unit_price') }}</th>
                        <th>{{ __('medicine.stored_date') }}</th>
                        <th>{{ __('medicine.expiry_date') }}</th>
                        <th>{{ __('medicine.status') }}</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $medicine)
                        <tr class="fade-in lively-hover">
                            <td>{{ $medicine->medical_id }}</td>
                            <td>
                                <strong>{{ $medicine->name }}</strong>
                            </td>
                            <td>{{ $medicine->formulation_strength }}</td>
                            <td>{{ $medicine->batch_number }}</td>
                            <td>
                                <span class="badge stock-badge lively-pop @if($medicine->quantity < 50) bg-danger @elseif($medicine->quantity < 100) bg-warning @else bg-success @endif">
                                    {{ $medicine->quantity }}
                                </span>
                            </td>
                            <td>{{ __('currency.tzs') }} {{ number_format($medicine->unit_price, 2) }}</td>
                            <td>
                                <small>{{ optional($medicine->stored_date) ? \App\Helpers\DateHelper::formatDate($medicine->stored_date) : __('common.na') }}</small>
                            </td>
                            <td>
                                <small>{{ \App\Helpers\DateHelper::formatDate($medicine->expiry_date) }}</small>
                            </td>
                            <td>
                                @if($medicine->isExpired())
                                    <span class="status-badge expired lively-pop">
                                        <i class="fas fa-times-circle"></i> {{ __('medicine.expired') }}
                                    </span>
                                @elseif($medicine->isExpiringSoon())
                                    <span class="status-badge expiring lively-pop">
                                        <i class="fas fa-clock"></i> {{ __('medicine.expiring_soon') }}
                                    </span>
                                @else
                                    <span class="status-badge active lively-pop">
                                        <i class="fas fa-check-circle"></i> {{ __('medicine.active') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('medicines.show', $medicine->id) }}" class="btn btn-sm btn-info text-white lively-pop" title="{{ __('common.edit') }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if (auth()->user()->role === 'procurement' || auth()->user()->role === 'admin')
                                    <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-sm btn-warning lively-pop" title="{{ __('common.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('medicines.destroy', $medicine->id) }}" style="display:inline;" onsubmit="return confirm('{{ __('alert.warning') }}: {{ __('common.delete') }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger lively-pop" title="{{ __('common.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-inbox lively-wiggle" style="font-size: 30px; color: #ccc;"></i>
                                <p class="mt-2 text-muted">{{ __('medicine.no_medicines') }}
                                    @if(auth()->user()->role === 'procurement' || auth()->user()->role === 'admin')
                                        <a href="{{ route('medicines.create') }}">{{ __('medicine.add_first') }}</a>
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endempty
            </tbody>
            </table>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">{{ __('dashboard.total_medicines') }}: {{ $medicines->total() }}</div>
                <div>
                    <nav aria-label="Medicines pagination">
                        @if($medicines->hasPages())
                            <ul class="pagination mb-0">
                                <li class="page-item {{ $medicines->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $medicines->previousPageUrl() ?: '#' }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo; {{ __('common.back') }}</span>
                                    </a>
                                </li>

                                @for ($p = 1; $p <= $medicines->lastPage(); $p++)
                                    @if($p == 1 || $p == $medicines->lastPage() || ($p >= $medicines->currentPage() - 1 && $p <= $medicines->currentPage() + 1))
                                        <li class="page-item {{ $medicines->currentPage() == $p ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $medicines->url($p) }}">{{ $p }}</a>
                                        </li>
                                    @elseif($p == 2 && $medicines->currentPage() > 3)
                                        <li class="page-item disabled"><span class="page-link">…</span></li>
                                    @elseif($p == $medicines->lastPage() - 1 && $medicines->currentPage() < $medicines->lastPage() - 2)
                                        <li class="page-item disabled"><span class="page-link">…</span></li>
                                    @endif
                                @endfor

                                <li class="page-item {{ $medicines->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $medicines->nextPageUrl() ?: '#' }}" aria-label="Next">
                                        <span aria-hidden="true">{{ __('common.back') == 'Back' ? 'Next' : 'Next' }} &raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
 
    </div>
</div>
@endsection

