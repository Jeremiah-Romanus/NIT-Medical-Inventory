@extends('layouts.layout')

@section('title', __('user.title'))
@section('page-title', __('user.title'))
@section('page-subtitle', __('user.subtitle'))

@section('content')
    <div class="card" x-data="{ search: '' }">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title">{{ __('user.title') }}</h5>
            <div class="d-flex align-items-center gap-2">
                <input type="text" x-model="search" class="form-control form-control-sm table-filter-input" placeholder="{{ __('common.search') }}">
                <span class="text-muted small">{{ $users->count() }} {{ __('user.total') }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('user.name') }}</th>
                            <th>{{ __('user.email') }}</th>
                            <th>{{ __('user.phone') }}</th>
                            <th>{{ __('user.role') }}</th>
                            <th>{{ __('user.joined') }}</th>
                            <th class="text-end">{{ __('user.update_role') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr x-show="!search || '{{ strtolower($user->name) }} {{ strtolower($user->email) }} {{ strtolower($user->role) }}'.includes(search.toLowerCase())" class="fade-in">
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    @switch($user->role)
                                        @case('admin') {{ __('role.admin') }} @break
                                        @case('procurement') {{ __('role.procurement') }} @break
                                        @default {{ __('role.pharmacist') }}
                                    @endswitch
                                </td>
                                <td>{{ \App\Helpers\DateHelper::formatDate($user->created_at) }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.users.role', $user) }}" class="d-inline-flex gap-2 align-items-center justify-content-end">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="form-select form-select-sm" style="min-width: 200px;">
                                            <option value="admin" @selected($user->role === 'admin')>{{ __('role.admin') }}</option>
                                            <option value="pharmacist" @selected($user->role === 'pharmacist')>{{ __('role.pharmacist') }}</option>
                                            <option value="procurement" @selected($user->role === 'procurement')>{{ __('role.procurement') }}</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">{{ __('user.save') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">{{ __('user.no_users') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection